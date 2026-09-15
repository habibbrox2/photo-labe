/**
 * Media grid + lightbox — Alpine components.
 * The grid holds the list of media items (passed from the blade template).
 * Clicking a thumbnail dispatches a custom event that the lightbox listens for.
 */
import Alpine from 'alpinejs';

Alpine.data('mediaGrid', () => ({
    items: [],

    init() {
        const script = this.$el.querySelector('script[data-media-grid]');
        if (script) {
            try { this.items = JSON.parse(script.textContent); } catch(e) { this.items = []; }
        }
    },

    openLightbox(index) {
        window.dispatchEvent(new CustomEvent('media-grid-open', { detail: { items: this.items, index } }));
    },
}));

Alpine.data('mediaLightbox', () => ({
    open: false,
    items: [],
    index: 0,

    init() {
        window.addEventListener('media-grid-open', (event) => {
            this.items = event.detail.items;
            this.index = event.detail.index;
            this.open = true;
            document.body.style.overflow = 'hidden';
        });
    },

    closeLightbox() {
        this.open = false;
        document.body.style.overflow = '';
    },

    prev() {
        if (this.items.length === 0) return;
        this.index = (this.index - 1 + this.items.length) % this.items.length;
    },

    next() {
        if (this.items.length === 0) return;
        this.index = (this.index + 1) % this.items.length;
    },

    current() {
        return this.items[this.index] || {};
    },
}));