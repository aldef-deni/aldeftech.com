// Aldef Store WhatsApp enquiry logic. Run with: npm run test:js
import { test } from 'node:test';
import assert from 'node:assert/strict';
import * as w from '../../resources/js/store-whatsapp.js';

const variants = [
  { size: '40', color: 'Hitam', available: true },
  { size: '42', color: 'Hitam', available: true },
  { size: '40', color: 'Putih', available: true },
  { size: '42', color: 'Putih', available: false },
];
const base = { variants, sizes: ['40', '42'], colors: ['Hitam', 'Putih'], quantity: 1, number: '628128968609' };

test('availability is checked per size + colour combination', () => {
  assert.equal(w.isCombinationAvailable(variants, '42', 'Putih'), false);
  assert.equal(w.isCombinationAvailable(variants, '42', 'Hitam'), true);
  // 42 exists (in black) and white exists (in 40), yet 42/white is sold out.
  assert.equal(w.isSizeSelectable(variants, '42', 'Putih'), false);
  assert.equal(w.isSizeSelectable(variants, '42', ''), true);
  assert.equal(w.isColorSelectable(variants, 'Putih', '42'), false);
  assert.equal(w.isColorSelectable(variants, 'Putih', '40'), true);
});

test('the button stays blocked until the selection is complete and in stock', () => {
  assert.equal(w.selectionProblem({ ...base, size: '', color: '' }), 'pick-color');
  assert.equal(w.selectionProblem({ ...base, size: '', color: 'Hitam' }), 'pick-size');
  assert.equal(w.selectionProblem({ ...base, size: '42', color: 'Putih' }), 'unavailable');
  assert.equal(w.selectionProblem({ ...base, size: '42', color: 'Hitam' }), null);
  assert.equal(w.selectionProblem({ ...base, size: '42', color: 'Hitam', number: null }), 'no-number');
  assert.equal(w.selectionProblem({ ...base, size: '42', color: 'Hitam', quantity: '0' }), 'quantity');
  assert.equal(w.selectionProblem({ ...base, size: '42', color: 'Hitam', quantity: '' }), 'quantity');
  // A product without variants only needs a quantity and a number.
  assert.equal(w.selectionProblem({ variants: [], sizes: [], colors: [], size: '', color: '', quantity: 2, number: '62811' }), null);
});

test('quantity is a whole number of at least 1', () => {
  assert.equal(w.clampQuantity('5000'), w.MAX_QUANTITY);
  assert.equal(w.clampQuantity('-3'), 1);
  assert.equal(w.clampQuantity('2.7'), 2);
  assert.equal(w.clampQuantity('abc'), 1);
});

test('message follows the template and drops empty lines', () => {
  const message = w.buildMessage({
    name: 'Sepatu Lari X', sku: 'ATS1210202600001', color: '', size: '42', sizeSystem: 'EU',
    quantity: 2, note: '   ', url: 'https://aldeftech.com/store/sepatu-lari-x',
  });
  assert.equal(message, [
    'Halo Aldef Store, saya ingin menanyakan sepatu ini:',
    '',
    '*Sepatu Lari X*',
    'Kode: ATS1210202600001',
    'Ukuran: 42 (EU)',
    'Jumlah: 2 pasang',
    '',
    'Link produk:',
    'https://aldeftech.com/store/sepatu-lari-x',
    '',
    'Mohon informasi harga dan ketersediaannya. Terima kasih.',
  ].join('\n'));
});

test('message names the brand, category and the photo on screen', () => {
  const message = w.buildMessage({
    name: 'Gel-Kayano 30', sku: 'ATS1', brand: 'Asics', category: 'Sepatu Lari › Road', color: 'Hitam',
    size: '42', sizeSystem: 'EU', quantity: 1, note: 'Kirim ke Bandung',
    url: 'https://aldeftech.com/store/gel-kayano-30', media: { number: 3, total: 6, type: 'image' },
  });
  assert.equal(message, [
    'Halo Aldef Store, saya ingin menanyakan sepatu ini:',
    '',
    '*Gel-Kayano 30*',
    'Brand: Asics',
    'Kategori: Sepatu Lari › Road',
    'Kode: ATS1',
    'Warna: Hitam',
    'Ukuran: 42 (EU)',
    'Jumlah: 1 pasang',
    'Catatan: Kirim ke Bandung',
    '',
    'Foto yang saya lihat (3 dari 6):',
    'https://aldeftech.com/store/gel-kayano-30?foto=3',
    '',
    'Mohon informasi harga dan ketersediaannya. Terima kasih.',
  ].join('\n'));

  // It is the only link, so WhatsApp previews that photo.
  assert.equal(message.match(/https?:\/\//g).length, 1);

  const video = w.buildMessage({ name: 'X', sku: 'S', quantity: 1, url: 'https://a.test/store/x', media: { number: 2, total: 4, type: 'video' } });
  assert.ok(video.includes('Video yang saya lihat (2 dari 4):\nhttps://a.test/store/x?foto=2'));
});

test('without size options the visitor types the size', () => {
  const free = { variants: [], sizes: [], colors: [], size: '', color: '', quantity: 1, number: '62811', askSize: true };
  assert.equal(w.selectionProblem({ ...free, customSize: '   ' }), 'type-size');
  assert.equal(w.selectionProblem({ ...free, customSize: '42' }), null);

  const message = w.buildMessage({ name: 'X', sku: 'S', customSize: '  42   wide ', sizeSystem: 'EU', quantity: 1, url: 'https://a.test/x' });
  assert.ok(message.includes('Ukuran: 42 wide (EU)'));
  assert.equal(w.cleanSize('x'.repeat(50)).length, w.MAX_SIZE_LENGTH);
});

test('special characters and line breaks are encoded for wa.me', () => {
  const message = w.buildMessage({
    name: 'Air & Run "Pro" #1 ½', sku: 'ATS1', color: 'Hitam/Putih', size: '42', sizeSystem: 'EU',
    quantity: 1, note: 'Kirim ke Bandung?\r\nTolong cek 100% ori & ada box 😀', url: 'https://aldeftech.com/store/a?x=1',
  });
  assert.ok(message.includes('Catatan: Kirim ke Bandung?\nTolong cek 100% ori & ada box 😀'));

  const url = w.buildWhatsAppUrl('+62 812-8968-609', message);
  assert.ok(url.startsWith('https://wa.me/628128968609?text='));
  const query = url.split('?text=')[1];
  assert.doesNotMatch(query, /[\s&#?"]/);
  assert.equal(decodeURIComponent(query), message);
  assert.equal(w.buildWhatsAppUrl('', 'x'), null);
});
