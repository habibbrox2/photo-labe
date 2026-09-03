const puppeteer = require('puppeteer-core');
const fs = require('fs');

const BASE = 'http://127.0.0.1:8899';
const CHROME = 'C:/Program Files/Google/Chrome/Application/chrome.exe';
const SHOTS = __dirname + '/shots';

const results = [];
const issues = { pageErrors: [], consoleErrors: [], serverErrors: [], clientErrors: [] };
let shotN = 0;

function record(name, pass, detail = '') {
  results.push({ name, pass: !!pass, detail });
  console.log((pass ? 'PASS ' : 'FAIL ') + name + (detail ? '  |  ' + detail : ''));
}
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

async function shot(page, label) {
  shotN++;
  const file = SHOTS + '/' + String(shotN).padStart(2, '0') + '-' + label.replace(/[^\w.-]+/g, '_') + '.png';
  try { await page.screenshot({ path: file }); return file; } catch (e) { return 'shot failed'; }
}

function wire(page) {
  page.on('pageerror', (e) => issues.pageErrors.push('pageerror: ' + e.message));
  page.on('console', (m) => { if (m.type() === 'error') issues.consoleErrors.push(m.text().slice(0, 300)); });
  page.on('response', (r) => {
    const s = r.status();
    if (s >= 500) issues.serverErrors.push(s + ' ' + r.url().slice(0, 160));
    else if (s >= 400) issues.clientErrors.push(s + ' ' + r.url().slice(0, 160));
  });
}

const BAD = ['Whoops, something went wrong', 'Server Error', 'Illuminate\\', 'The page has expired', 'Stack trace'];

async function visit(page, path, opts = {}) {
  const { expectText = null, expectUrl = null, label = path, shotName = null, minLen = 100, settle = 900 } = opts;
  try {
    const resp = await page.goto(BASE + path, { waitUntil: 'domcontentloaded', timeout: 30000 });
    await sleep(settle);
    const status = resp ? resp.status() : 0;
    const finalUrl = page.url();
    const bodyText = await page.evaluate(() => document.body ? document.body.innerText : '');
    let ok = status >= 200 && status < 400;
    let detail = 'status ' + status + ', url ' + finalUrl.replace(BASE, '');
    if (expectUrl) { const m = finalUrl.includes(expectUrl); ok = ok && m; detail += (m ? ', url-ok' : ', URL MISS ' + expectUrl); }
    if (expectText) { const h = bodyText.includes(expectText); ok = ok && h; detail += (h ? ', text-ok' : ', TEXT MISS: ' + expectText); }
    if (bodyText.length < minLen) { ok = false; detail += ', body too short (' + bodyText.length + ')'; }
    for (const b of BAD) { if (bodyText.includes(b)) { ok = false; detail += ', ERROR: ' + b; break; } }
    record(label, ok, detail);
    if (shotName) await shot(page, shotName);
    return { status, finalUrl, bodyText };
  } catch (e) {
    record(label, false, 'exception: ' + e.message);
    return { status: 0, finalUrl: page.url(), bodyText: '' };
  }
}

async function fillFields(page, fields) {
  for (const [name, value] of Object.entries(fields)) {
    const el = await page.$('input[name="' + name + '"], select[name="' + name + '"], textarea[name="' + name + '"]');
    if (!el) continue;
    const info = await el.evaluate((x) => ({ tag: x.tagName, type: x.type }));
    if (info.tag === 'SELECT') {
      await page.select('select[name="' + name + '"]', String(value));
    } else if (info.tag === 'INPUT' && info.type === 'radio') {
      const ok = await page.evaluate(([n, v]) => {
        const el = document.querySelector('input[name="' + n + '"][value="' + v + '"]');
        if (!el) return false;
        el.click();
        return true;
      }, [name, value]);
      if (!ok) throw new Error('radio ' + name + '=' + value + ' not found');
    } else {
      // clear reliably (fields may be pre-filled via old()/auth) then type
      await el.evaluate((x) => { x.value = ''; });
      await el.focus();
      await el.type(String(value), { delay: 4 });
    }
  }
}

const lastPreCheck = { data: null };

async function submitForm(page, actionPart, { fields = {}, waitMs = 2000 } = {}) {
  await page.waitForSelector('form[action*="' + actionPart + '"]', { timeout: 8000 });
  await fillFields(page, fields);
  lastPreCheck.data = await page.evaluate((part) => {
    const f = document.querySelector('form[action*="' + part + '"]');
    if (!f) return null;
    const out = [];
    for (const el of f.querySelectorAll('input, select, textarea')) {
      if (el.required || /radio|checkbox/.test(el.type)) {
        out.push({ name: el.name, type: el.type, value: el.type === 'password' ? '***' : el.value, valid: el.checkValidity() });
      }
    }
    return { formValid: f.checkValidity(), fields: out };
  }, actionPart);
  await Promise.all([
    page.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 25000 }).catch(() => {}),
    page.evaluate((part) => {
      const form = document.querySelector('form[action*="' + part + '"]');
      if (!form) throw new Error('form not found');
      const btn = form.querySelector('button[type="submit"]');
      if (!btn) throw new Error('no submit button');
      btn.click();
    }, actionPart),
  ]);
  await sleep(waitMs);
  return page.url();
}

async function login(page, email, password) {
  await page.goto(BASE + '/login', { waitUntil: 'domcontentloaded' });
  await sleep(500);
  return submitForm(page, '/login', { fields: { email, password }, waitMs: 1800 });
}

(async () => {
  if (!fs.existsSync(SHOTS)) fs.mkdirSync(SHOTS, { recursive: true });
  const browser = await puppeteer.launch({
    executablePath: CHROME,
    headless: 'new',
    args: ['--no-sandbox', '--disable-gpu', '--disable-dev-shm-usage'],
    defaultViewport: { width: 1440, height: 1000 },
  });

  try {
    // ============ 1. GUEST: public pages (default context, no cookies) ============
    const pg = await browser.newPage();
    wire(pg);
    await visit(pg, '/', { shotName: 'home', minLen: 400 });
    await visit(pg, '/about', { label: 'about' });
    await visit(pg, '/services', { expectText: 'Retouching', shotName: 'services', label: 'services' });
    await visit(pg, '/services/professional-photo-retouching', { expectText: 'Professional Photo Retouching', label: 'service-detail' });
    await visit(pg, '/products', { expectText: 'Presets', shotName: 'products', label: 'products' });
    await visit(pg, '/products/cinematic-film-presets', { expectText: 'Add to Cart', label: 'product-detail', shotName: 'product-detail' });
    await visit(pg, '/portfolio', { label: 'portfolio', minLen: 60 });
    await visit(pg, '/before-after', { label: 'before-after', minLen: 60 });
    await visit(pg, '/blog', { label: 'blog', minLen: 60 });
    await visit(pg, '/pricing', { label: 'pricing', minLen: 60 });
    await visit(pg, '/faq', { label: 'faq', minLen: 60 });
    await visit(pg, '/contact', { label: 'contact', minLen: 60 });
    await visit(pg, '/cart', { label: 'cart-empty', shotName: 'cart-empty' });

    // ============ 2. GUEST: protected areas + quote submit must bounce to login ============
    await visit(pg, '/account', { expectUrl: '/login', label: 'guest-account-redirect', minLen: 0 });
    await visit(pg, '/admin', { expectUrl: '/login', label: 'guest-admin-redirect', minLen: 0 });
    const gq = await visit(pg, '/get-a-quote', { label: 'quote-form-guest', minLen: 200 });
    if (gq.status === 200) {
      await submitForm(pg, 'get-a-quote', {
        fields: { name: 'Guest Tester', email: 'guest@test.com', quantity: '1', requirements: 'guest attempt' }, waitMs: 1200,
      });
      record('guest-quote-submit-blocked', pg.url().includes('/login'), pg.url().replace(BASE, ''));
    } else {
      record('guest-quote-submit-blocked', false, 'form status ' + gq.status);
    }
    await pg.close();

    // ============ 3. ADMIN (isolated context) ============
    const ctxA = await browser.createBrowserContext();
    const pa = await ctxA.newPage();
    wire(pa);
    const adminUrl = await login(pa, 'admin@photolabe.com', 'password');
    record('admin-login-redirect', adminUrl.includes('/admin'), adminUrl.replace(BASE, ''));
    await visit(pa, '/admin', { label: 'admin-dashboard', shotName: 'admin-dashboard', minLen: 120 });
    await visit(pa, '/admin/quotes', { label: 'admin-quotes', shotName: 'admin-quotes', minLen: 60 });
    await visit(pa, '/admin/orders', { label: 'admin-orders', shotName: 'admin-orders', minLen: 60 });
    await visit(pa, '/admin/customers', { label: 'admin-customers', minLen: 60 });
    await visit(pa, '/admin/notifications', { label: 'admin-notifications', minLen: 60 });
    await visit(pa, '/admin/products', { label: 'admin-products', minLen: 60 });
    await ctxA.close();

    // ============ 4. CUSTOMER John (isolated context) ============
    const ctxC = await browser.createBrowserContext();
    const pc = await ctxC.newPage();
    wire(pc);
    const custUrl = await login(pc, 'john@example.com', 'password');
    record('customer-login', !custUrl.includes('/login'), custUrl.replace(BASE, ''));
    await visit(pc, '/account', { label: 'account-dashboard', shotName: 'account-dashboard', minLen: 120 });
    await visit(pc, '/account/notifications', { label: 'account-notifications', shotName: 'account-notifications', minLen: 40 });
    await visit(pc, '/account/quotes', { label: 'account-quotes', minLen: 40 });
    await visit(pc, '/account/purchases', { label: 'account-purchases', minLen: 40 });
    await visit(pc, '/account/payments', { label: 'account-payments', minLen: 40 });
    await visit(pc, '/account/profile', { label: 'account-profile', minLen: 40 });

    // ============ 5. CUSTOMER: submit a real quote through the UI ============
    await pc.goto(BASE + '/get-a-quote', { waitUntil: 'domcontentloaded' });
    await sleep(700);
    const serviceId = await pc.evaluate(() => {
      const s = document.querySelector('select[name="service_id"]');
      return s && s.options.length > 1 ? s.options[1].value : '1';
    });
    await submitForm(pc, 'get-a-quote', {
      fields: { name: 'John Customer', email: 'john@example.com', quantity: '1', service_id: serviceId, requirements: 'Browser end-to-end quote request - please review.' },
      waitMs: 2500,
    });
    const qBody = await pc.evaluate(() => document.body.innerText);
    record('customer-quote-submit', qBody.includes('submitted successfully'), pc.url().replace(BASE, ''));
    await shot(pc, 'quote-submitted');

    // ============ 6. CUSTOMER: add to cart + checkout ============
    await pc.goto(BASE + '/products/cinematic-film-presets', { waitUntil: 'domcontentloaded' });
    await sleep(700);
    await submitForm(pc, '/cart', { fields: {}, waitMs: 2000 }); // add-to-cart form
    const cartBody = await pc.evaluate(() => document.body.innerText);
    record('customer-add-to-cart', cartBody.includes('Cinematic Film Presets'), pc.url().replace(BASE, ''));
    await shot(pc, 'cart-with-item');

    const proceed = await pc.evaluate(() => !!Array.from(document.querySelectorAll('a')).find((a) => a.textContent.includes('Proceed to Checkout')));
    if (proceed) {
      await Promise.all([
        pc.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 20000 }).catch(() => {}),
        pc.evaluate(() => Array.from(document.querySelectorAll('a')).find((a) => a.textContent.includes('Proceed to Checkout')).click()),
      ]);
      await sleep(1500);
      const onCheckout = pc.url().includes('/checkout');
      record('checkout-page', onCheckout, pc.url().replace(BASE, ''));
      if (onCheckout) {
        await shot(pc, 'checkout-page');
        let postStatus = null;
        const onResp = (r) => { if (r.request().method() === 'POST' && r.url().includes('/checkout')) postStatus = r.status(); };
        pc.on('response', onResp);
        await submitForm(pc, '/checkout', {
          fields: { name: 'John Customer', email: 'john@example.com', phone: '1234567890', address: 'Browser test address', payment_method: 'manual' },
          waitMs: 3500,
        });
        pc.off('response', onResp);
        const oBody = await pc.evaluate(() => document.body.innerText);
        const oUrl = pc.url().replace(BASE, '');
        let detail = 'post=' + postStatus + ' url=' + oUrl;
        if (!oBody.includes('Order Confirmed!')) {
          const errs = await pc.evaluate(() =>
            Array.from(document.querySelectorAll('p.text-red-500, .text-red-500, [class*="alert"]'))
              .map((e) => e.textContent.trim()).filter((t) => t)
          );
          detail += ' errs=' + JSON.stringify([...new Set(errs)].slice(0, 6));
          if (lastPreCheck.data) {
            const bad = lastPreCheck.data.fields.filter((x) => !x.valid || (x.required && !x.value));
            detail += ' invalidFields=' + JSON.stringify(bad);
          }
        }
        record('place-order', oBody.includes('Order Confirmed!'), detail);
        if (oBody.includes('Order Confirmed!')) await shot(pc, 'order-confirmed');
      }
    } else {
      record('checkout-page', false, 'no Proceed to Checkout link');
    }

    // ============ 7. CUSTOMER: order now listed in account ============
    await visit(pc, '/account/orders', { label: 'account-orders', shotName: 'account-orders', minLen: 40 });
    const ordersBody = await pc.evaluate(() => document.body.innerText);
    record('order-in-account', ordersBody.includes('ORD-'), '');
    await ctxC.close();

    // ============ 8. SECURITY: other customer cannot reach /admin ============
    const ctxS = await browser.createBrowserContext();
    const ps = await ctxS.newPage();
    wire(ps);
    await login(ps, 'jane@example.com', 'password');
    const res = await ps.goto(BASE + '/admin', { waitUntil: 'domcontentloaded' }).catch(() => null);
    await sleep(800);
    const code = res ? res.status() : 0;
    record('security-customer-to-admin', code === 403 || code === 302, 'status ' + code);
    await ctxS.close();
  } catch (e) {
    record('fatal', false, e.message);
  } finally {
    await browser.close();
  }

  console.log('\n=============== SUMMARY ===============');
  const fails = results.filter((r) => !r.pass);
  console.log('TOTAL ' + results.length + ', PASS ' + (results.length - fails.length) + ', FAIL ' + fails.length);
  for (const f of fails) console.log('  FAIL: ' + f.name + (f.detail ? '  |  ' + f.detail : ''));
  console.log('Screenshots: ' + SHOTS);

  console.log('\n--- JS page errors (' + issues.pageErrors.length + ') ---');
  [...new Set(issues.pageErrors)].slice(0, 8).forEach((e) => console.log('  ' + e));
  console.log('--- console.error (' + issues.consoleErrors.length + ') ---');
  [...new Set(issues.consoleErrors)].slice(0, 8).forEach((e) => console.log('  ' + e));
  console.log('--- HTTP 5xx (' + issues.serverErrors.length + ') ---');
  [...new Set(issues.serverErrors)].slice(0, 8).forEach((e) => console.log('  ' + e));
  console.log('--- HTTP 4xx (' + issues.clientErrors.length + ') ---');
  [...new Set(issues.clientErrors)].slice(0, 15).forEach((e) => console.log('  ' + e));

  process.exit(fails.length ? 1 : 0);
})();
