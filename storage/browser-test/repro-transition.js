const puppeteer = require('puppeteer-core');
const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';

(async () => {
  const browser = await puppeteer.launch({ executablePath: CHROME, headless: 'new', args: ['--no-sandbox', '--hide-scrollbars'] });

  async function run(label, path, width, interact) {
    const page = await browser.newPage();
    await page.setViewport({ width, height: 900 });
    const errs = [];
    page.on('console', (m) => { if (m.type() === 'error') errs.push(m.text().slice(0, 220)); });
    page.on('pageerror', (e) => errs.push('pageerror: ' + e.message.slice(0, 220)));
    await page.goto(BASE + path, { waitUntil: 'networkidle0', timeout: 30000 });
    await new Promise((r) => setTimeout(r, 700));
    await interact(page);
    await new Promise((r) => setTimeout(r, 1200));
    const relevant = errs.filter((e) => e.includes('isFromCancelledTransition') || e.includes('Uncaught'));
    console.log((relevant.length ? '*** ' : '    ') + label + ' (' + path + ')' + (relevant.length ? ' -> ' + JSON.stringify(relevant.slice(0, 3)) : ' clean'));
    await page.close();
    return relevant.length;
  }

  let bad = 0;
  const searchToggle = async (page, times = 4) => {
    for (let i = 0; i < times; i++) {
      await page.evaluate(() => { const b = document.querySelector('button[aria-controls="site-search"], button[aria-label*="search" i]'); if (b) b.click(); });
      await new Promise((r) => setTimeout(r, 60));
    }
  };
  const mobileToggle = async (page, times = 4) => {
    for (let i = 0; i < times; i++) {
      await page.evaluate(() => { const b = document.querySelector('button[aria-controls="mobile-navigation"]'); if (b) b.click(); });
      await new Promise((r) => setTimeout(r, 60));
    }
  };
  const faqToggle = async (page, times = 5) => {
    for (let i = 0; i < times; i++) {
      await page.evaluate(() => { const btns = Array.from(document.querySelectorAll('button')); const f = btns.find((b) => b.textContent.includes('How long') || b.textContent.includes('project take')); if (f) f.click(); });
      await new Promise((r) => setTimeout(r, 50));
    }
  };
  const filters = async (page, times = 8) => {
    for (let i = 0; i < times; i++) {
      await page.evaluate((k) => { const btns = Array.from(document.querySelectorAll('button')); const f = btns.find((b) => b.textContent.includes(k) && b.textContent.trim().length < 40); if (f) f.click(); }, i % 2 ? 'All' : 'Photo');
      await new Promise((r) => setTimeout(r, 50));
    }
  };
  const beforeAfterDrag = async (page) => {
    // click & drag slider line? simpler: click center of slider handle zone
    for (let i = 0; i < 4; i++) {
      await page.mouse.click(400 + i * 100, 300);
      await new Promise((r) => setTimeout(r, 40));
    }
  };

  bad += await run('desktop search toggle', '/', 1440, searchToggle) > 0 ? 1 : 0;
  bad += await run('mobile menu toggle', '/', 390, mobileToggle) > 0 ? 1 : 0;
  bad += await run('home FAQ toggle', '/', 1440, faqToggle) > 0 ? 1 : 0;
  bad += await run('services filters', '/services', 1440, filters) > 0 ? 1 : 0;
  bad += await run('portfolio filters', '/portfolio', 1440, filters) > 0 ? 1 : 0;
  bad += await run('before-after home drag', '/', 1440, beforeAfterDrag) > 0 ? 1 : 0;
  await browser.close();
  console.log('\npages with error:', bad);
})();
