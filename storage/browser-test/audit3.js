const puppeteer = require('puppeteer-core');
const { PNG } = require('pngjs');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

const PAGES = [
  ['/', 'home'],
  ['/services', 'services-index'],
  ['/products', 'products-index'],
  ['/portfolio', 'portfolio-index'],
  ['/blog', 'blog-index'],
  ['/about', 'about'],
  ['/faq', 'faq'],
  ['/pricing', 'pricing'],
  ['/before-after', 'before-after'],
  ['/get-a-quote', 'quote'],
  ['/contact', 'contact'],
  ['/login', 'login'],
  ['/register', 'register'],
  ['/cart', 'cart'],
  ['/checkout', 'checkout'],
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
function px(png, x, y) {
  x = Math.round(x); y = Math.round(y);
  if (x < 0 || y < 0 || x >= png.width || y >= png.height) return null;
  const i = (png.width * y + x) * 4;
  return { r: png.data[i], g: png.data[i + 1], b: png.data[i + 2] };
}
function modal(arr) {
  const m = new Map();
  for (const c of arr) if (c) {
    const k = (c.r << 16) | (c.g << 8) | c.b;
    m.set(k, (m.get(k) || 0) + 1);
  }
  let best = null, bn = 0;
  for (const [k, n] of m) if (n > bn) { bn = n; best = k; }
  if (!best) return null;
  return { r: (best >> 16) & 255, g: (best >> 8) & 255, b: best & 255 };
}
function parseRGB(str) {
  const m = str.match(/rgba?\(([^)]+)\)/);
  if (!m) return null;
  const p = m[1].split(',').map((x) => parseFloat(x));
  return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
}

async function headerSample(page, label) {
  const nav = await page.evaluate(() => {
    const h = document.querySelector('header');
    if (!h) return null;
    const a = h.querySelector('nav a');
    if (!a) return null;
    const r = a.getBoundingClientRect();
    return { x: r.left + r.width / 2, y: r.top + r.height / 2 };
  });
  if (!nav) return null;
  const raw = await page.screenshot({ type: 'png' });
  const png = PNG.sync.read(Buffer.from(raw));
  const bg = px(png, nav.x, nav.y);
  const white = { r: 255, g: 255, b: 255, a: 0.8 };
  const eff = { r: Math.round(white.r * 0.8 + bg.r * 0.2), g: Math.round(white.g * 0.8 + bg.g * 0.2), b: Math.round(white.b * 0.8 + bg.b * 0.2) };
  return { bg: `rgb(${bg.r},${bg.g},${bg.b})`, ratio: Math.round(contrast(eff, bg) * 100) / 100 };
}

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox', '--hide-scrollbars'] });
  for (const [path, label] of PAGES) {
    const page = await browser.newPage();
    await page.setViewport({ width: 1440, height: 900 });
    const out = { page: label, path };
    try {
      const resp = await page.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 30000 });
      out.status = resp ? resp.status() : 0;
      await page.evaluate(() => document.fonts.ready);
      await new Promise((r) => setTimeout(r, 500));

      // font families
      out.fonts = await page.evaluate(() => {
        const bad = [];
        const seen = new Set();
        const wk = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        let n;
        while ((n = wk.nextNode())) {
          const t = (n.textContent || '').trim();
          if (!t) continue;
          const el = n.parentElement;
          if (!el || el.closest('script,style,svg,noscript,pre,code,option')) continue;
          const fam = getComputedStyle(el).fontFamily;
          if (seen.has(fam)) continue;
          seen.add(fam);
          const first = fam.split(',')[0].replace(/["']/g, '').trim().toLowerCase();
          if (first === 'inter' || first === 'ui-monospace' || first === 'ui-sans-serif' || first === 'system-ui' || first.startsWith('apple')) continue;
          if (bad.length < 8) bad.push(fam.slice(0, 100) + '  ::  ' + el.tagName + ' ' + t.slice(0, 30));
        }
        return { inter: document.fonts.check('16px Inter'), bad };
      });

      // header contrast: rest + scrolled
      out.headerRest = await headerSample(page, 'rest');
      await page.evaluate(() => window.scrollTo(0, 600));
      await new Promise((r) => setTimeout(r, 450));
      out.headerScrolled = await headerSample(page, 'scrolled');
      await page.evaluate(() => window.scrollTo(0, 0));
      await new Promise((r) => setTimeout(r, 300));

      // reveal everything (disable Alpine x-show hiding) then full-page shot
      await page.addStyleTag({ content: '[x-show]{display:block !important;opacity:1 !important;transform:none !important} [x-cloak]{display:block !important}' });
      await new Promise((r) => setTimeout(r, 900));
      const metas = await page.evaluate(() => {
        const parseRGB = (str) => {
          const m = str.match(/rgba?\(([^)]+)\)/);
          if (!m) return null;
          const p = m[1].split(',').map((x) => parseFloat(x));
          return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
        };
        const out = [];
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        while (walker.nextNode()) {
          const n = walker.currentNode;
          const t = (n.textContent || '').trim();
          if (!t || t.length > 200) continue;
          const el = n.parentElement;
          if (!el || el.closest('script,style,svg,noscript,option,button,[x-cloak]')) continue;
          const cs = getComputedStyle(el);
          const r = el.getBoundingClientRect();
          if (r.width < 6 || r.height < 6) continue;
          if (cs.visibility === 'hidden' || cs.display === 'none' || parseFloat(cs.opacity) < 0.05) continue;
          const fs = parseFloat(cs.fontSize);
          if (!(fs >= 11)) continue;
          const c = parseRGB(cs.color);
          if (!c || c.a < 0.1) continue;
          out.push({
            top: r.top + window.scrollY, left: r.left + window.scrollX,
            w: r.width, h: r.height, fs, col: [c.r, c.g, c.b, c.a],
            tag: el.tagName, cls: String(el.className).slice(0, 70), t: t.slice(0, 90),
          });
        }
        return out;
      });
      const raw = await page.screenshot({ type: 'png', fullPage: true });
      const fp = PNG.sync.read(Buffer.from(raw));
      const flags = [];
      const seen = new Set();
      for (const m of metas) {
        // sample an interior grid of the element box (glyph ink is the minority -> modal = bg)
        const pts = [];
        for (let gy = 1; gy <= 3; gy++) for (let gx = 1; gx <= 8; gx++) {
          pts.push(px(fp, m.left + (m.w * gx) / 9, m.top + (m.h * gy) / 4));
        }
        const bg = modal(pts);
        if (!bg) continue;
        const a = m.col[3];
        const eff = a < 1
          ? { r: m.col[0] * a + bg.r * (1 - a), g: m.col[1] * a + bg.g * (1 - a), b: m.col[2] * a + bg.b * (1 - a) }
          : { r: m.col[0], g: m.col[1], b: m.col[2] };
        const ratio = contrast(eff, bg);
        const minOk = m.fs >= 18.5 ? 3 : 4.5;
        if (ratio < minOk) {
          const k = m.cls + '|' + m.t;
          if (seen.has(k)) continue;
          seen.add(k);
          flags.push({ tag: m.tag, cls: m.cls, t: m.t, fs: Math.round(m.fs), ratio: Math.round(ratio * 100) / 100, col: `rgb(${m.col[0]},${m.col[1]},${m.col[2]})`, bg: `rgb(${bg.r},${bg.g},${bg.b})` });
        }
      }
      flags.sort((x, y) => x.ratio - y.ratio);
      out.flags = flags.slice(0, 20);
      out.flagCount = flags.length;
      console.log(JSON.stringify(out));
    } catch (e) {
      out.error = e.message;
      console.log(JSON.stringify(out));
    }
    await page.close();
  }
  await browser.close();
})();
