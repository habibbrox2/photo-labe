const puppeteer = require('puppeteer-core');
const fs = require('fs');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const ROUTES = fs.readFileSync(__dirname + '/routes.txt', 'utf8').split('\n').filter(Boolean);

function fillParam(uri) {
  return uri.replace(/\{[^}]+\}/g, '1');
}

async function login(page, email, password) {
  await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded' });
  await new Promise((r) => setTimeout(r, 400));
  await page.evaluate(([e, pw]) => {
    const f = document.querySelector('form[action*="/login"]');
    f.querySelector('input[name="email"]').value = e;
    f.querySelector('input[name="password"]').value = pw;
    f.querySelector('button[type="submit"]').click();
  }, [email, password]);
  await page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => {});
  await new Promise((r) => setTimeout(r, 800));
}

async function probe(page, path, issues, seen, cur) {
  if (seen.has(path)) return;
  seen.add(path);
  const url = path.startsWith('http') ? path : BASE + '/' + path.replace(/^\//, '');
  cur.now = path;
  const label = path.slice(0, 90);
  try {
    const resp = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 25000 });
    await new Promise((r) => setTimeout(r, 350));
    const s = resp ? resp.status() : 0;
    if (s >= 500) issues.push('500  ' + label);
    else if (s === 404 && !/\/\{/.test(path) && !path.includes('/1/1')) {
      // ignore expected 404s for non-existent ids only if route requires a record
      const body = await page.evaluate(() => document.body ? document.body.innerText.slice(0, 400) : '');
      if (body.includes('Sorry, the page you are looking for could not be found') || body.length < 120) {
        if (/\/1($|\/)/.test(path)) issues.push('404  ' + label + '  (record id may not exist)');
      } else issues.push('404  ' + label);
    }
  } catch (e) {
    issues.push('EXC  ' + label + ' :: ' + e.message.slice(0, 120));
  }
}

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox', '--hide-scrollbars'] });
  const issues = [];
  const seen = new Set();
  const wire = (page, tag, cur) => {
    page.on('pageerror', (e) => issues.push('JSPAGEERROR [' + tag + ' ' + cur.now + '] ' + e.message.slice(0, 160)));
    page.on('console', (m) => { if (m.type() === 'error') issues.push('JSCONSOLE [' + tag + ' ' + cur.now + '] ' + m.text().slice(0, 200)); });
    page.on('response', (r) => { if (r.status() >= 500) issues.push('HTTP' + r.status() + ' [' + tag + ' ' + cur.now + '] ' + r.url().replace(BASE, '').slice(0, 140)); });
  };

  // ---------- GUEST ----------
  {
    const ctx = await browser.createBrowserContext();
    const page = await ctx.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    const cur = { now: '/login' };
    wire(page, 'guest', cur);
    const guest = ROUTES.filter((u) => !u.startsWith('admin') && !u.startsWith('account') && !u.startsWith('customer') && !u.includes('{') && !u.startsWith('_') && !u.startsWith('sanctum') && !/^\/?(login|register|forgot-password|reset-password)/.test(u));
    for (const u of guest) {
      if (u === '/') continue;
      await probe(page, u, issues, seen, cur);
    }
    // slug detail pages
    for (const u of ['/services/professional-photo-retouching', '/products/cinematic-film-presets', '/portfolio/brand-identity-design', '/blog/color-correction-mastery', '/page/about']) {
      await probe(page, u, issues, seen, cur);
    }
    await ctx.close();
    console.log('guest sweep done');
  }

  // ---------- ADMIN ----------
  {
    const ctx = await browser.createBrowserContext();
    const page = await ctx.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    const cur = { now: '/login' };
    wire(page, 'admin', cur);
    await login(page, 'admin@photolabe.com', 'password');
    const adminRoutes = ROUTES.filter((u) => u.startsWith('admin') && u !== 'admin');
    for (const u of adminRoutes) {
      await probe(page, fillParam(u), issues, seen, cur);
    }
    await ctx.close();
    console.log('admin sweep done');
  }

  // ---------- CUSTOMER ----------
  {
    const ctx = await browser.createBrowserContext();
    const page = await ctx.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    const cur = { now: '/login' };
    wire(page, 'customer', cur);
    await login(page, 'john@example.com', 'password');
    const custRoutes = ROUTES.filter((u) => u.startsWith('account') || u.startsWith('customer') || u.startsWith('cart') || u.startsWith('checkout'));
    for (const u of custRoutes) {
      await probe(page, fillParam(u), issues, seen, cur);
    }
    await ctx.close();
    console.log('customer sweep done');
  }

  await browser.close();
  console.log('\n===== ISSUES (' + issues.length + ') =====');
  const uniq = [...new Set(issues)];
  for (const i of uniq) console.log(i);
  console.log('===== END =====');
})();
