/*
 * Aldef Store: variant availability and the WhatsApp enquiry message.
 *
 * Pure functions, no DOM: the product page's Alpine component calls them and
 * they can be exercised directly from Node.
 *
 * A variant is { size, color, available }. An empty size or colour means the
 * product has no such option.
 */

export const MAX_QUANTITY = 999;
export const MAX_NOTE_LENGTH = 500;
export const MAX_SIZE_LENGTH = 20;

/** The exact size/colour combination exists and is in stock. */
export function isCombinationAvailable(variants, size, color) {
  return variants.some((v) => v.available && v.size === size && v.color === color);
}

/**
 * Can this size still be picked? Only when some in-stock variant has it —
 * together with the chosen colour, once a colour is chosen. Checking size and
 * colour separately would let "42 / Putih" through when 42 exists only in
 * black and white exists only in 40.
 */
export function isSizeSelectable(variants, size, selectedColor) {
  return variants.some((v) => v.available && v.size === size && (!selectedColor || v.color === selectedColor));
}

export function isColorSelectable(variants, color, selectedSize) {
  return variants.some((v) => v.available && v.color === color && (!selectedSize || v.size === selectedSize));
}

export function clampQuantity(value) {
  const n = Number.parseInt(String(value ?? '').trim(), 10);
  if (!Number.isFinite(n) || n < 1) return 1;
  return Math.min(n, MAX_QUANTITY);
}

/** A size typed by the visitor, for products without size options. */
export function cleanSize(value) {
  return String(value ?? '').replace(/\s+/g, ' ').trim().slice(0, MAX_SIZE_LENGTH);
}

export function cleanNote(value) {
  return String(value ?? '')
    // Control characters other than line breaks have no place in a chat.
    .replace(/[\u0000-\u0009\u000b-\u001f\u007f]/g, '')
    .replace(/\r\n?/g, '\n')
    .replace(/\n{3,}/g, '\n\n')
    .trim()
    .slice(0, MAX_NOTE_LENGTH);
}

/**
 * Why the visitor cannot ask yet, or null when the selection is complete and
 * in stock. The product page shows this text next to the disabled button.
 */
export function selectionProblem({ variants, sizes, colors, size, color, quantity, number, askSize = false, customSize = '' }) {
  if (!number) return 'no-number';
  if (colors.length > 0 && !color) return 'pick-color';
  if (sizes.length > 0 && !size) return 'pick-size';
  // Without size options the visitor types the size they want.
  if (askSize && sizes.length === 0 && !cleanSize(customSize)) return 'type-size';
  if (clampQuantity(quantity) !== Number(quantity)) return 'quantity';
  if (variants.length > 0 && !isCombinationAvailable(variants, size || '', color || '')) return 'unavailable';
  return null;
}

/**
 * The message, with lines that would say nothing left out. A chat link can
 * only carry text, so the photo the visitor was looking at travels as a link:
 * the only link in the message, which WhatsApp shows as a preview of that
 * photo (the page at that address offers it as its og:image).
 *
 * media: { number, total, type } of the photo or video on screen, or null.
 */
export function buildMessage({
  name, sku, brand = '', category = '', color, size, customSize = '', sizeSystem, quantity, note, url,
  media = null,
}) {
  const lines = [
    'Halo Aldef Store, saya ingin menanyakan sepatu ini:',
    '',
    `*${name}*`,
  ];

  if (brand) lines.push(`Brand: ${brand}`);
  if (category) lines.push(`Kategori: ${category}`);
  lines.push(`Kode: ${sku}`);
  if (color) lines.push(`Warna: ${color}`);

  const wanted = size || cleanSize(customSize);
  if (wanted) lines.push(`Ukuran: ${sizeSystem ? `${wanted} (${sizeSystem})` : wanted}`);
  lines.push(`Jumlah: ${clampQuantity(quantity)} pasang`);

  const cleaned = cleanNote(note);
  if (cleaned) lines.push(`Catatan: ${cleaned}`);

  lines.push('');
  if (media && media.total > 0) {
    const kind = media.type === 'video' ? 'Video' : 'Foto';
    lines.push(`${kind} yang saya lihat (${media.number} dari ${media.total}):`, `${url}?foto=${media.number}`);
  } else {
    lines.push('Link produk:', url);
  }

  lines.push('', 'Mohon informasi harga dan ketersediaannya. Terima kasih.');

  return lines.join('\n');
}

export function buildWhatsAppUrl(number, message) {
  const digits = String(number ?? '').replace(/\D/g, '');
  if (!digits) return null;
  return `https://wa.me/${digits}?text=${encodeURIComponent(message)}`;
}
