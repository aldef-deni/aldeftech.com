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
export function selectionProblem({ variants, sizes, colors, size, color, quantity, number }) {
  if (!number) return 'no-number';
  if (colors.length > 0 && !color) return 'pick-color';
  if (sizes.length > 0 && !size) return 'pick-size';
  if (clampQuantity(quantity) !== Number(quantity)) return 'quantity';
  if (variants.length > 0 && !isCombinationAvailable(variants, size || '', color || '')) return 'unavailable';
  return null;
}

/** The message, with the colour and note lines left out when they say nothing. */
export function buildMessage({ name, sku, color, size, sizeSystem, quantity, note, url }) {
  const lines = [
    'Halo, saya tertarik dengan produk sepatu di website Aldeftech.',
    '',
    `Produk: ${name}`,
    `Kode: ${sku}`,
  ];

  if (color) lines.push(`Warna: ${color}`);
  if (size) lines.push(`Ukuran: ${sizeSystem ? `${size} (${sizeSystem})` : size}`);
  lines.push(`Jumlah: ${clampQuantity(quantity)} pasang`);

  const cleaned = cleanNote(note);
  if (cleaned) lines.push(`Catatan: ${cleaned}`);

  lines.push(`Link produk: ${url}`, '', 'Mohon informasi harga dan ketersediaannya. Terima kasih.');

  return lines.join('\n');
}

export function buildWhatsAppUrl(number, message) {
  const digits = String(number ?? '').replace(/\D/g, '');
  if (!digits) return null;
  return `https://wa.me/${digits}?text=${encodeURIComponent(message)}`;
}
