const puppeteer = require('puppeteer-core');
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
];

const AUDIT_FN = () => {
  const parseRGBA = (s) => {
    const m = s.match(/rgba?\(([^)]+)\)/);
    if (!m) return null;
    const p = m[1].split(',').map((x) => parseFloat(x));
    return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
  };
  const lum = (c) => {
    const f = (v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
    return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b);
  };
  const contrast = (a, b) => {
    const la = lum(a), lb = lum(b);
    const hi = Math.max(la, lb), lo = Math.min(la, lb);
    return (hi + 0.05) / (lo + 0.05);
  };
  const resolveVars = (el, str, depth) => {
    if (depth > 5) return str;
    const m = str.match(/var\((--[a-zA-Z0-9-_]+)\)/);
    if (!m) return str;
    const v = getComputedStyle(el).getPropertyValue(m[1]).trim();
    return resolveVars(el, str.replace(m[0], v || ''), depth + 1);
  };
  const firstColor = (el, img) => {
    let resolved = img;
    if (resolved.includes('var(')) resolved = resolveVars(el, resolved, 0);
    const m = resolved.match(/gradient\(([^)]*)\)/);
    if (!m) return null;
    const stops = m[1].split(/,(?![^(]*\))/);
    const cols = stops.map((s) => s.trim()).filter((s) => /^(rgba?\(|#)/i.test(s));
    return cols[0] || null;
  };
  const toRGBA = (str) => {
    if (!str) return null;
    if (str.startsWith('#')) {
      let h = str.slice(1).split(/[\s,)]/)[0];
      if (h.length === 3 || h.length === 4) h = h.split('').map((c) => c + c).join('');
      const n = parseInt(h, 16);
      if (h.length === 6) return { r: (n >> 16) & 255, g: (n >> 8) & 255, b: n & 255, a: 1 };
      if (h.length === 8) return { r: (n >> 24) & 255, g: (n >> 16) & 255, b: (n >> 8) & 255, a: (n & 255) / 255 };
      return null;
    }
    return parseRGBA(str);
  };
  const composite = (colors) => {
    let r = 255, g = 255, b = 255;
    for (const c of colors) {
      if (!c || c.a <= 0) continue;
      r = c.r * c.a + r * (1 - c.a);
      g = c.g * c.a + g * (1 - c.a);
      b = c.b * c.a + b * (1 - c.a);
    }
    return { r: Math.round(r), g: Math.round(g), b: Math.round(b), a: 1 };
  };
  const stackAt = (x, y) => {
    const layers = [];
    let el = document.elementFromPoint(x, y);
    let guard = 0;
    while (el && guard++ < 14) {
      const cs = getComputedStyle(el);
      const bgc = cs.backgroundColor;
      if (bgc !== 'rgba(0, 0, 0, 0)' && bgc !== 'transparent') layers.push({ c: parseRGBA(bgc), on: el.tagName + '.' + String(el.className).slice(0, 40) });
      else if (cs.backgroundImage && cs.backgroundImage !== 'none') {
        const g = firstColor(el, cs.backgroundImage);
        if (g) { const c = toRGBA(g); if (c) layers.push({ c, gradient: cs.backgroundImage.slice(0, 60), on: el.tagName + '.' + String(el.className).slice(0, 40) }); }
      }
      if (el === document.body) break;
      el = el.parentElement;
    }
    return layers;
  };
  return { stackAt, toRGBA, parseRGBA, contrast, composite };
};

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
      await new Promise((r) => setTimeout(r, 300));

      // ---- FONT AUDIT ----
      out.fonts = await page.evaluate(() => {
        const bad = [];
        const seen = new Set();
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
        let n;
        while ((n = walker.nextNode())) {
          const t = (n.textContent || '').trim();
          if (!t) continue;
          const el = n.parentElement;
          if (!el || el.closest('script,style,svg,noscript,pre,code')) continue;
          const cs = getComputedStyle(el);
          const fam = cs.fontFamily;
          if (seen.has(fam)) continue;
          seen.add(fam);
          const first = fam.split(',')[0].replace(/["']/g, '').trim().toLowerCase();
          if (first === 'inter' || first === 'ui-monospace' || first === 'ui-sans-serif' || first === 'system-ui' || first.startsWith('apple')) continue;
          if (bad.length < 15) bad.push({ fam: fam.slice(0, 140), cls: (el.className || '').toString().slice(0, 90), tag: el.tagName, sample: t.slice(0, 40) });
        }
        return { interLoaded: document.fonts.check('16px Inter'), bad: bad.length, badFamilies: bad };
      });

      // ---- HEADER CONTRAST (hide the fixed header, sample what is behind it) ----
      const headerInfo = await page.evaluate((helpersSrc) => {
        const H = eval('(' + helpersSrc + ')')();
        const header = document.querySelector('header');
        const res = { present: !!header };
        if (!header) return res;
        res.headerBgNow = getComputedStyle(header).backgroundColor;
        const link = header.querySelector('nav a');
        if (link) {
          const r = link.getBoundingClientRect();
          const x = Math.round(r.left + r.width / 2);
          const y = Math.round(r.top + r.height / 2);
          const prev = header.style.display;
          header.style.display = 'none'; // so elementFromPoint hits page content
          const layers = H.stackAt(x, y);
          header.style.display = prev;
          const textC = H.parseRGBA(getComputedStyle(link).color) || H.parseRGBA('rgba(255,255,255,0.8)');
          if (textC && layers.length) {
            const bg = H.composite(layers.map((l) => l.c));
            res.ratio = Math.round(H.contrast(textC, bg) * 100) / 100;
            res.textColor = getComputedStyle(link).color;
            res.bg = 'rgb(' + bg.r + ',' + bg.g + ',' + bg.b + ')';
            res.layers = layers.map((l) => ({ bg: l.on, rgba: JSON.stringify(l.c) }));
          } else {
            res.noSample = { layers: layers.map((l) => ({ on: l.on, rgba: JSON.stringify(l.c) })) };
          }
        }
        return res;
      }, AUDIT_FN.toString());
      out.header = headerInfo;

      const errs = [];
      page.on('pageerror', (e) => errs.push(e.message.slice(0, 200)));
      console.log(JSON.stringify(out));
    } catch (e) {
      out.error = e.message;
      console.log(JSON.stringify(out));
    }
    await page.close();
  }
  await browser.close();
})();
