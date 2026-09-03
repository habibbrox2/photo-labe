const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox'] });
  const ctx = await browser.createBrowserContext();
  const page = await ctx.newPage();

  // login as john
  await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded' });
  await sleep(400);
  await page.evaluate(() => {
    const f = document.querySelector('form[action*="/login"]');
    f.querySelector('input[name="email"]').value = 'john@example.com';
    f.querySelector('input[name="password"]').value = 'password';
  });
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.evaluate(() => document.querySelector('form[action*="/login"] button[type="submit"]').click()),
  ]);
  await sleep(1000);
  console.log('after login:', page.url());

  // ensure cart has item
  await page.goto(BASE + '/products/cinematic-film-presets', { waitUntil: 'domcontentloaded' });
  await sleep(500);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.evaluate(() => document.querySelector('form[action*="/cart"] button[type="submit"]').click()),
  ]);
  await sleep(1000);
  console.log('after add-to-cart:', page.url());

  await page.goto(BASE + '/checkout', { waitUntil: 'domcontentloaded' });
  await sleep(800);

  // fill form fields directly and capture the POST response
  await page.evaluate(() => {
    const f = document.querySelector('form[action*="/checkout"]');
    f.querySelector('input[name="name"]').value = 'John Customer';
    f.querySelector('input[name="email"]').value = 'john@example.com';
    f.querySelector('input[name="phone"]').value = '1234567890';
    f.querySelector('textarea[name="address"]').value = 'Browser test address';
  });
  const resp = await page.evaluate(async () => {
    const f = document.querySelector('form[action*="/checkout"]');
    const fd = new FormData(f);
    const r = await fetch(f.action, { method: 'POST', body: fd, redirect: 'manual' });
    return { status: r.status, type: r.type, url: r.url };
  });
  console.log('checkout POST response:', JSON.stringify(resp));

  // now follow manually to see where it lands
  if (resp.status >= 300 && resp.status < 400) {
    const target = resp.url;
    const r2 = await page.goto(target, { waitUntil: 'domcontentloaded' }).catch(() => null);
    await sleep(1000);
    console.log('followed redirect to:', page.url(), 'status', r2 ? r2.status() : '?');
    const txt = await page.evaluate(() => document.body.innerText);
    console.log('body head:', txt.slice(0, 600).replace(/\n+/g, ' | '));
  } else {
    const txt = await page.evaluate(() => document.body.innerText);
    console.log('no redirect; body head:', txt.slice(0, 900).replace(/\n+/g, ' | '));
  }
  await browser.close();
})();
