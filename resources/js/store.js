import {
  buildMessage,
  buildWhatsAppUrl,
  clampQuantity,
  isColorSelectable,
  isSizeSelectable,
  selectionProblem,
} from './store-whatsapp';

/**
 * Product page of Aldef Store: gallery, size/colour/quantity picker and the
 * WhatsApp enquiry button. Configuration comes from the page as JSON.
 */
export function registerStore(Alpine) {
  Alpine.data('storeProduct', (config) => ({
    variants: config.variants ?? [],
    sizes: config.sizes ?? [],
    colors: config.colors ?? [],
    size: '',
    color: '',
    quantity: 1,
    note: '',
    image: 0,
    viewer: null, // index open in the full-screen popup, or null
    touched: false,

    init() {
      // The page must not scroll behind the popup.
      this.$watch('viewer', (value) => {
        document.body.style.overflow = value === null ? '' : 'hidden';
      });

      // A single option is not a choice; pick it when it is in stock.
      if (this.colors.length === 1 && isColorSelectable(this.variants, this.colors[0], '')) {
        this.color = this.colors[0];
      }
      if (this.sizes.length === 1 && isSizeSelectable(this.variants, this.sizes[0], this.color)) {
        this.size = this.sizes[0];
      }
    },

    sizeSelectable(size) {
      return isSizeSelectable(this.variants, size, this.color);
    },

    colorSelectable(color) {
      return isColorSelectable(this.variants, color, this.size);
    },

    pickSize(size) {
      if (!this.sizeSelectable(size)) return;
      this.size = this.size === size ? '' : size;
    },

    pickColor(color) {
      if (!this.colorSelectable(color)) return;
      this.color = this.color === color ? '' : color;
      // The chosen size may not exist in the new colour.
      if (this.size && !this.sizeSelectable(this.size)) this.size = '';
    },

    step(delta) {
      this.quantity = clampQuantity(clampQuantity(this.quantity) + delta);
    },

    fixQuantity() {
      this.quantity = clampQuantity(this.quantity);
    },

    get problem() {
      return selectionProblem({
        variants: this.variants,
        sizes: this.sizes,
        colors: this.colors,
        size: this.size,
        color: this.color,
        quantity: this.quantity,
        number: config.number,
      });
    },

    get problemText() {
      return config.messages[this.problem] ?? '';
    },

    get canAsk() {
      return this.problem === null;
    },

    // Rebuilt from the latest selection every time it is read.
    get whatsappUrl() {
      if (!this.canAsk) return null;

      return buildWhatsAppUrl(config.number, buildMessage({
        name: config.name,
        sku: config.sku,
        color: this.color,
        size: this.size,
        sizeSystem: config.sizeSystem,
        quantity: this.quantity,
        note: this.note,
        url: config.url,
      }));
    },

    ask(event) {
      this.touched = true;
      if (!this.canAsk) event.preventDefault();
    },

    show(index) {
      const count = config.imageCount ?? 0;
      if (count === 0) return;
      this.image = (index + count) % count;
    },

    // Popup: a photo shown large, or a video with its player. Only the open
    // item's <video> exists in the DOM, so closing or moving on stops it.
    openViewer(index) {
      this.show(index);
      this.viewer = this.image;
      this.$nextTick(() => this.$refs.viewerClose?.focus());
    },

    closeViewer() {
      this.viewer = null;
    },

    viewerStep(delta) {
      if (this.viewer === null) return;
      this.show(this.viewer + delta);
      this.viewer = this.image;
    },
  }));
}
