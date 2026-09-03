const puppeteer = require('puppeteer-core');
const { PNG } = require('pngjs');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

const VIEWPORTS = [
  [1440, 900],
  [390, 844],
];

async function runAudit(page, path, label, vw) {
  const out = { page: label, path, vw };
  const resp = await page.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 30000 });
  out.status = resp ? resp.status() : 0;
  await page.evaluate(() => document.fonts.ready);
  await new Promise((r) => setTimeout(r, 500));

  out.fonts = await page.evaluate(() => {
    const bad = [];
    const seen = new Set();
    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    let n;
    while ((n = walker.nextNode())) {
      const t = (n.textContent || '').trim();
      if (!t) continue;
      const el = n.parentElement;
      if (!el || el.closest('script,style,svg,noscript,pre,code,option')) continue;
      const cs = getComputedStyle(el);
      const fam = cs.fontFamily;
      if (seen.has(fam)) continue;
      seen.add(fam);
      const first = fam.split(',')[0].replace(/["']/g, '').trim().toLowerCase();
      if (first === 'inter' || first === 'ui-monospace' || first === 'ui-sans-serif' || first === 'system-ui' || first.startsWith('apple')) continue;
      if (bad.length < 10) bad.push({ fam: fam.slice(0, 120), tag: el.tagName, cls: String(el.className).slice(0, 60), sample: t.slice(0, 30) });
    }
    return { interLoaded: document.fonts.check('16px Inter'), bad: bad.length, list: bad };
  });

  // header bg band pixels: sample row just under the logo/nav text vertical center
  const raw = await page.screenshot({ type: 'png' });
  const png = PNG.sync.read(Buffer.from(raw));
  const yTop = Math.min(40, Math.floor(png.height / 2) - 1);
  const row = [];
  for (const fx of [0.15, 0.4, 0.6, 0.85]) {
    const x = Math.round(png.width * fx);
    if (x < png.width && yTop < png.height) {
      const i = (png.width * yTop + x) * 4;
      row.push({ fx, rgb: `rgb(${png.data[i]},${png.data[i + 1]},${png.data[i + 2]})` });
    }
  }
  out.headerRow = row;
  return out;
}

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox', '--hide-scrollbars'] });
  const paths = [
    ['/login', 'login'],
    ['/register', 'register'],
    ['/cart', 'cart'],
    ['/checkout', 'checkout'],
    ['/account', 'account(dash)'],
    ['/customer/orders', 'customer-orders'],
  ];
  for (const [path, label] of paths) {
    for (const [w, h] of VIEWPORTS) {
      const page = await browser.newPage();
      await page.setViewport({ width: w, height: h });
      try {
        const out = await runAudit(page, path, label, w);
        console.log(JSON.stringify(out));
      } catch (e) {
        console.log(JSON.stringify({ page: label, path, vw: w, error: e.message }));
      }
      await page.close();
    }
  }
  await browser.close();
})();
