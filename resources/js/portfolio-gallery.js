/**
 * Portfolio gallery lightbox — Alpine component.
 * The list of image URLs is passed in from the page: x-data="portfolioGallery([...])".
 */
import Alpine from 'alpinejs';

Alpine.data('portfolioGallery', (images = []) => ({
    lightboxOpen: false,
    currentIndex: 0,
    images,

    openLightbox(index) {
        if (!this.images.length) return;
        this.currentIndex = index;
        this.lightboxOpen = true;
        document.body.style.overflow = 'hidden';
    },

    closeLightbox() {
        this.lightboxOpen = false;
        document.body.style.overflow = '';
    },

    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },

    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    },
}));
