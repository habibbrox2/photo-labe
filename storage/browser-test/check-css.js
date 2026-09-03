const fs = require('fs');
const path = require('path');
const root = path.resolve(__dirname, '..', '..');
const file = fs.readFileSync(path.join(root, 'resources/views/frontend/portfolio/index.blade.php'), 'utf8');
const assetsDir = path.join(root, 'public/build/assets');
const cssFiles = fs.readdirSync(assetsDir).filter(f => f.endsWith('.css'));
const css = cssFiles.map(f => fs.readFileSync(path.join(assetsDir, f), 'utf8')).join('\n');
const attrRe = /(?:class|:class)="([^"]*)"/g;
const tokens = new Set();
let m;
while ((m = attrRe.exec(file))) {
  for (const t of m[1].split(/\s+/)) {
    if (/^((hover|group-hover|focus|active|md|lg|sm|xl|2xl|max-md|max-lg):)*[a-z0-9][a-z0-9\-/\[\].]*$/.test(t) && !/[={}'?]/.test(t)) {
      tokens.add(t);
    }
  }
}
const missing = [];
for (const t of tokens) {
  const esc = t.replace(/[\[/:\]\.]/g, c => '\\' + c);
  if (!css.includes(esc)) missing.push(t);
}
console.log('unique tokens:', tokens.size);
console.log('missing from CSS:', JSON.stringify(missing));
