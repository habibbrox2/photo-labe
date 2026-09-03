const puppeteer = require('puppeteer-core');
const { PNG } = require('pngjs');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

const PAGES = [
  ['/', 'home'],
  ['/services', 'services-index'],
  ['/services/detail', 'services-show'],
  ['/products', 'products-index'],
  ['/products/detail', 'products-show'],
  ['/portfolio', 'portfolio-index'],
  ['/portfolio/detail', 'portfolio-show'],
  ['/blog', 'blog-index'],
  ['/blog/detail', 'blog-show'],
  ['/about', 'about'],
  ['/faq', 'faq'],
  ['/pricing', 'pricing'],
  ['/before-after', 'before-after'],
  ['/get-a-quote', 'quote'],
  ['/contact', 'contact'],
];

function lum(c) {
  const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
  return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b);
}
function contrast(a, b) {
  const la = lum(a), lb = lum(b);
  const hi = Math.max(la, lb), lo = Math.min(la, lb);
  return (hi + 0.05) / (lo + 0.05);
}
function blend(fg, bg) {
  const a = fg.a;
  return { r: Math.round(fg.r * a + bg.r * (1 - a)), g: Math.round(fg.g * a + bg.g * (1 - a)), b: Math.round(fg.b * a + bg.b * (1 - a)) };
}
function px(png, x, y) {
  const i = (png.width * y + x) * 4;
  return { r: png.data[i], g: png.data[i + 1], b: png.data[i + 2], a: 1 };
}

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox', '--hide-scrollbars'] });
  for (const [path, label] of PAGES) {
    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    const out = { page: label, path };
    try {
      let target = path;
      if (path.endsWith('/detail')) {
        const section = path.split('/')[1];
        const idx = await page.goto(BASE + '/' + section, { waitUntil: 'domcontentloaded', timeout: 30000 });
        const href = await page.evaluate((sec) => {
          const a = Array.from(document.querySelectorAll('a[href*="/' + sec + '/"]'));
          return a.length ? a[0].getAttribute('href') : null;
        }, section);
        target = href || path;
      }
      const resp = await page.goto(BASE + target, { waitUntil: 'networkidle0', timeout: 30000 });
      out.path = target;
      out.status = resp ? resp.status() : 0;
      await new Promise((r) => setTimeout(r, 600));

      // find nav link coords (desktop)
      const linkPos = await page.evaluate(() => {
        const header = document.querySelector('header');
        if (!header) return null;
        const link = header.querySelector('nav a');
        if (!link) return null;
        const r = link.getBoundingClientRect();
        return { x: Math.round(r.left + r.width / 2), y: Math.round(r.top + r.height / 2) };
      });
      const raw = await page.screenshot({ type: 'png' });
      const png = PNG.sync.read(Buffer.from(raw));
      const navWhite = { r: 255, g: 255, b: 255, a: 0.8 };
      if (linkPos && linkPos.y < png.height && linkPos.x < png.width) {
        const bg = px(png, linkPos.x, linkPos.y);
        const textRendered = blend(navWhite, bg);
        const ratio = contrast(textRendered, bg);
        out.nav = { x: linkPos.x, y: linkPos.y, bg: `rgb(${bg.r},${bg.g},${bg.b})`, ratio: Math.round(ratio * 100) / 100 };
      } else {
        out.nav = { none: true, pos: linkPos };
      }
      // row of pixels across header band center (y=50 desktop h-20 ~ 80px, so y=40) to catch mixed areas
      const samples = [];
      const yTop = 40;
      for (const fx of [0.1, 0.25, 0.5, 0.75, 0.9]) {
        const x = Math.round(png.width * fx);
        if (yTop < png.height && x < png.width) {
          const bg = px(png, x, yTop);
          samples.push({ fx, rgb: `rgb(${bg.r},${bg.g},${bg.b})` });
        }
      }
      out.headerBand = samples;
      console.log(JSON.stringify(out));
    } catch (e) {
      out.error = e.message;
      console.log(JSON.stringify(out));
    }
    await page.close();
  }
  await browser.close();
})();
