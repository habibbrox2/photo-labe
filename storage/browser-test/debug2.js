const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox'] });
  const ctx = await browser.createBrowserContext();
  const page = await ctx.newPage();

  let postInfo = null;
  page.on('response', (r) => {
    if (r.request().method() === 'POST' && r.url().includes('/checkout')) {
      postInfo = { status: r.status(), location: r.headers()['location'] || '' };
    }
  });

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
  await sleep(900);

  await page.goto(BASE + '/checkout', { waitUntil: 'domcontentloaded' });
  await sleep(700);
  await page.evaluate(() => {
    const f = document.querySelector('form[action*="/checkout"]');
    f.querySelector('input[name="name"]').value = 'John Customer';
    f.querySelector('input[name="email"]').value = 'john@example.com';
    f.querySelector('input[name="phone"]').value = '1234567890';
    f.querySelector('textarea[name="address"]').value = 'Browser test address';
  });
  await page.evaluate(() => document.querySelector('form[action*="/checkout"] button[type="submit"]').click());
  await sleep(2500);
  console.log('POST info:', JSON.stringify(postInfo));
  console.log('final url:', page.url());
  const errs = await page.evaluate(() =>
    Array.from(document.querySelectorAll('p, .error, [class*="red"]')).map((e) => e.textContent.trim()).filter((t) => t && t.length < 200)
  );
  console.log('error-ish texts:', JSON.stringify([...new Set(errs)].slice(0, 20), null, 0));
  await browser.close();
})();
