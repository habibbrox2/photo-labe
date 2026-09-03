const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

const PAGES = [
  { path: '/', label: 'home' },
  { path: '/before-after', label: 'before-after' },
  { path: '/products/portrait-perfection-presets', label: 'product' },
];

async function countAndTestSliders(page, label, width) {
  // scroll through the page so x-intersect reveal animations fire
  await page.evaluate(async () => {
    document.documentElement.style.scrollBehavior = 'auto';
    const h = document.body.scrollHeight;
    for (let y = 0; y < h; y += 500) { scrollTo(0, y); await new Promise(r => setTimeout(r, 40)); }
    scrollTo(0, 0);
  });
  await new Promise(r => setTimeout(r, 800));

  const total = await page.evaluate(() =>
    [...document.querySelectorAll('[x-data*="beforeAfterSlider"]')].filter(el => el.offsetParent !== null).length
  );
  if (!total) { console.log(`[${label} ${width}px] 0 sliders`); return { ok: true, note: 'no sliders' }; }

  const results = [];
  const n = await page.evaluate(() => document.querySelectorAll('[x-data*="beforeAfterSlider"]').length);
  for (let i = 0; i < n; i++) {
    const r = await page.evaluate(async (i) => {
      const el = document.querySelectorAll('[x-data*="beforeAfterSlider"]')[i];
      el.scrollIntoView({ block: 'center' });
      await new Promise(res => setTimeout(res, 350));
      const rect = el.getBoundingClientRect();
      if (rect.top > innerHeight || rect.bottom < 0) return { skip: 'offscreen' };
      // images loaded?
      const imgs = [...el.querySelectorAll('img')];
      const imgState = imgs.map(im => im.complete && im.naturalWidth > 0 ? 'ok' : 'BROKEN');
      const pos0 = el._x_dataStack[0].pos;
      return { pos0, imgState };
    }, i);
    if (r?.skip) continue;

    const circle = await page.$(`[x-data*="beforeAfterSlider"]:nth-of-type(${i + 1}) .w-10`);
    // nth-of-type unreliable across siblings; instead pick by walking from matching evaluate
    const picked = await page.evaluate((idx) => {
      const el = document.querySelectorAll('[x-data*="beforeAfterSlider"]')[idx];
      const c = el.querySelector('.w-10');
      const cb = c.getBoundingClientRect();
      return { x: cb.x + cb.width / 2, y: cb.y + cb.height / 2, idx };
    }, i);
    await page.mouse.move(picked.x, picked.y);
    await page.mouse.down();
    for (let s = 1; s <= 6; s++) await page.mouse.move(picked.x + s * 40, picked.y, { steps: 2 });
    await page.mouse.up();
    const after = await page.evaluate((idx) => {
      const el = document.querySelectorAll('[x-data*="beforeAfterSlider"]')[idx];
      return el._x_dataStack[0].pos;
    }, i);
    results.push({ slider: i, pos: `${r.pos0}->${after}`, imgs: r.imgState.join(',') });
  }
  console.log(`[${label} ${width}px] visible sliders: ${total}, drags:`, JSON.stringify(results));
  return { ok: results.every(x => x.pos.split('->')[1] !== x.pos.split('->')[0] && x.imgs === 'ok,ok'), results };
}

(async () => {
  const b = await puppeteer.launch({ executablePath: CHROME, headless: 'new' });
  let failed = 0;

  for (const { path, label } of PAGES) {
    const p = await b.newPage();
    const errs = [];
    p.on('pageerror', e => errs.push(String(e).slice(0, 120)));
    p.on('console', m => { if (m.type() === 'error') errs.push(m.text().slice(0, 120)); });
    await p.setViewport({ width: 1440, height: 800 });
    await p.goto(BASE + path, { waitUntil: 'networkidle0' });
    const res = await countAndTestSliders(p, label, 1440);
    if (!res.ok) failed++;
    if (errs.length) { console.log(`[${label}] ERRORS:`, JSON.stringify(errs)); failed++; }
    await p.close();
  }

  // mobile touch test on /before-after
  const m = await b.newPage();
  const merrs = [];
  m.on('pageerror', e => merrs.push(String(e).slice(0, 120)));
  await m.setViewport({ width: 390, height: 844, isMobile: true, hasTouch: true });
  await m.goto(BASE + '/before-after', { waitUntil: 'networkidle0' });
  await m.evaluate(() => { document.documentElement.style.scrollBehavior = 'auto'; const el = document.querySelector('[x-data*="beforeAfterSlider"]'); scrollTo(0, el.getBoundingClientRect().top + scrollY - 150); });
  await new Promise(r => setTimeout(r, 500));
  const tpos = await m.evaluate(async () => {
    const el = document.querySelector('[x-data*="beforeAfterSlider"]');
    const cb = el.querySelector('.w-10').getBoundingClientRect();
    const touch = new Touch({ identifier: 1, target: el, clientX: cb.x + cb.width / 2, clientY: cb.y + cb.height / 2 });
    const tstart = new TouchEvent('touchstart', { touches: [touch], targetTouches: [touch], changedTouches: [touch], bubbles: true, cancelable: true });
    el.querySelector('.w-10').dispatchEvent(tstart);
    for (let i = 1; i <= 6; i++) {
      const t = new Touch({ identifier: 1, target: el, clientX: cb.x + cb.width / 2 + i * 45, clientY: cb.y + cb.height / 2 });
      const ev = new TouchEvent('touchmove', { touches: [t], targetTouches: [t], changedTouches: [t], bubbles: true, cancelable: true });
      document.dispatchEvent(ev);
    }
    const p0 = el._x_dataStack[0].pos;
    return p0;
  });
  console.log(`[mobile 390px] touch drag pos after: ${tpos}`);
  if (tpos <= 50) failed++;
  if (merrs.length) { console.log('[mobile] ERRORS:', JSON.stringify(merrs)); failed++; }
  await m.close();

  console.log(failed === 0 ? '\nALL SLIDER CHECKS PASSED' : `\n${failed} CHECK(S) FAILED`);
  await b.close();
  process.exit(failed === 0 ? 0 : 1);
})().catch(e => { console.error('FATAL', e); process.exit(1); });
