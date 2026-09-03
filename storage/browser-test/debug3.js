const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox'] });
  const ctx = await browser.createBrowserContext();
  const page = await ctx.newPage();
  let postStatus = null;
  page.on('response', (r) => {
    if (r.request().method() === 'POST' && r.url().includes('/checkout')) postStatus = r.status();
  });

  // login john
  await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded' });
  await sleep(400);
  await page.type('input[name="email"]', 'john@example.com');
  await page.type('input[name="password"]', 'password');
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.evaluate(() => document.querySelector('form[action*="/login"] button[type="submit"]').click()),
  ]);
  await sleep(900);

  // add product
  await page.goto(BASE + '/products/cinematic-film-presets', { waitUntil: 'domcontentloaded' });
  await sleep(600);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.evaluate(() => document.querySelector('form[action*="/cart"] button[type="submit"]').click()),
  ]);
  await sleep(900);

  await page.goto(BASE + '/checkout', { waitUntil: 'domcontentloaded' });
  await sleep(700);

  // fill like test.js does (type into fields, click radio)
  for (const [name, value] of Object.entries({ name: 'John Customer', email: 'john@example.com', phone: '1234567890', address: 'Browser test address', payment_method: 'manual' })) {
    const el = await page.$('input[name="' + name + '"], textarea[name="' + name + '"]');
    if (!el) continue;
    const info = await el.evaluate((x) => ({ tag: x.tagName, type: x.type }));
    if (info.type === 'radio') {
      await page.evaluate(([n, v]) => document.querySelector('input[name="' + n + '"][value="' + v + '"]').click(), [name, value]);
    } else {
      await el.click({ clickCount: 3 });
      await page.keyboard.press('Backspace');
      await el.type(String(value), { delay: 4 });
    }
  }

  // submit and capture
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 25000 }).catch(() => {}),
    page.evaluate(() => document.querySelector('form[action*="/checkout"] button[type="submit"]').click()),
  ]);
  await sleep(2500);
  console.log('POST /checkout status:', postStatus);
  console.log('final url:', page.url());
  const errs = await page.evaluate(() =>
    Array.from(document.querySelectorAll('p, .text-red-500, .error, [class*="error"], [class*="alert"]'))
      .map((e) => e.textContent.trim()).filter((t) => t && t.length < 200)
  );
  console.log('page error-ish snippets:', JSON.stringify([...new Set(errs)].slice(0, 15)));
  await browser.close();
})();
