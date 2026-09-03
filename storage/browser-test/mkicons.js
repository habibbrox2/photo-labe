// Generate simple branded PNG icons (indigo square with lighter inner square)
const zlib = require('zlib');
const fs = require('fs');

function crc32(buf) {
  let c, table = crc32.table;
  if (!table) {
    table = crc32.table = [];
    for (let n = 0; n < 256; n++) {
      c = n;
      for (let k = 0; k < 8; k++) c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1;
      table[n] = c >>> 0;
    }
  }
  let crc = 0xffffffff;
  for (let i = 0; i < buf.length; i++) crc = table[(crc ^ buf[i]) & 0xff] ^ (crc >>> 8);
  return (crc ^ 0xffffffff) >>> 0;
}

function chunk(type, data) {
  const len = Buffer.alloc(4);
  len.writeUInt32BE(data.length);
  const t = Buffer.from(type, 'ascii');
  const body = Buffer.concat([t, data]);
  const crc = Buffer.alloc(4);
  crc.writeUInt32BE(crc32(body));
  return Buffer.concat([len, body, crc]);
}

function png(size, rgb, inner) {
  const raw = Buffer.alloc(size * (size * 4 + 1));
  const m = size * 0.24;
  for (let y = 0; y < size; y++) {
    raw[y * (size * 4 + 1)] = 0;
    for (let x = 0; x < size; x++) {
      const o = y * (size * 4 + 1) + 1 + x * 4;
      const inside = x >= m && x < size - m && y >= m && y < size - m;
      const c = inside ? inner : rgb;
      raw[o] = c[0]; raw[o + 1] = c[1]; raw[o + 2] = c[2]; raw[o + 3] = 255;
    }
  }
  const ihdr = Buffer.alloc(13);
  ihdr.writeUInt32BE(size, 0);
  ihdr.writeUInt32BE(size, 4);
  ihdr[8] = 8;  // bit depth
  ihdr[9] = 6;  // color type RGBA
  return Buffer.concat([
    Buffer.from([0x89, 0x50, 0x4e, 0x47, 0x0d, 0x0a, 0x1a, 0x0a]),
    chunk('IHDR', ihdr),
    chunk('IDAT', zlib.deflateSync(raw)),
    chunk('IEND', Buffer.alloc(0)),
  ]);
}

const indigo = [0x4c, 0x6e, 0xf5];
const light = [0xa5, 0xb4, 0xfc];
const dest = 'public';
const targets = {
  'favicon-16x16.png': 16,
  'favicon-32x32.png': 32,
  'favicon-192x192.png': 192,
  'favicon-512x512.png': 512,
  'apple-touch-icon.png': 180,
  'mstile-144x144.png': 144,
};
for (const [file, size] of Object.entries(targets)) {
  fs.writeFileSync(dest + '/' + file, png(size, indigo, light));
  console.log('wrote', dest + '/' + file, size + 'x' + size);
}
