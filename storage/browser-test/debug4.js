const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox'] });
  const ctx = await browser.createBrowserContext();
  const page = await ctx.newPage();

  async function loginAsJohn() {
    await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded' });
    await sleep(400);
    await page.type('input[name="email"]', 'john@example.com');
    await page.type('input[name="password"]', 'password');
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
      page.evaluate(() => document.querySelector('form[action*="/login"] button[type="submit"]').click()),
    ]);
    await sleep(900);
  }
  await loginAsJohn();
  await page.goto(BASE + '/products/cinematic-film-presets', { waitUntil: 'domcontentloaded' });
  await sleep(600);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
    page.evaluate(() => document.querySelector('form[action*="/cart"] button[type="submit"]').click()),
  ]);
  await sleep(900);
  await page.goto(BASE + '/checkout', { waitUntil: 'domcontentloaded' });
  await sleep(700);

  async function fill(name, value) {
    const el = await page.$('input[name="' + name + '"], textarea[name="' + name + '"]');
    if (!el) return console.log('MISSING field', name);
    const info = await el.evaluate((x) => ({ tag: x.tagName, type: x.type }));
    if (info.type === 'radio') {
      await page.evaluate(([n, v]) => document.querySelector('input[name="' + n + '"][value="' + v + '"]').click(), [name, value]);
      return;
    }
    await el.click({ clickCount: 3 });
    await page.keyboard.press('Backspace');
    await el.type(String(value), { delay: 3 });
  }
  await fill('name', 'John Customer');
  await fill('email', 'john@example.com');
  await fill('phone', '1234567890');
  await fill('address', 'Browser test address');
  await fill('payment_method', 'manual');

  const report = await page.evaluate(() => {
    const f = document.querySelector('form[action*="/checkout"]');
    const out = [];
    for (const el of f.querySelectorAll('input, select, textarea')) {
      out.push({
        name: el.name, type: el.type, value: el.value,
        required: el.required, valid: el.checkValidity ? el.checkValidity() : true,
        msg: el.validationMessage || '',
      });
    }
    return { formValid: f.checkValidity(), fields: out };
  });
  console.log('formValid:', report.formValid);
  for (const fld of report.fields) {
    if (!fld.valid || fld.required) console.log(' ', JSON.stringify(fld));
  }
  await browser.close();
})();
