/**
 * Before/After Image Slider — Alpine component.
 * Supports mouse drag, touch drag, and keyboard navigation.
 */
import Alpine from 'alpinejs';

Alpine.data('beforeAfterSlider', () => ({
    pos: 50,
    isDragging: false,
    containerWidth: 0,

    init() {
        this.containerWidth = this.$el.offsetWidth;

        // Keep stable references so listeners can be removed in destroy().
        this.handlers = {
            onResize: () => this.updateContainerWidth(),
            onMouseMove: (e) => this.onDrag(e),
            onMouseUp: () => this.stopDrag(),
            onTouchMove: (e) => this.onDrag(e),
            onTouchEnd: () => this.stopDrag(),
        };

        window.addEventListener('resize', this.handlers.onResize);
        document.addEventListener('mousemove', this.handlers.onMouseMove);
        document.addEventListener('mouseup', this.handlers.onMouseUp);
        document.addEventListener('touchmove', this.handlers.onTouchMove, { passive: false });
        document.addEventListener('touchend', this.handlers.onTouchEnd);
    },

    destroy() {
        window.removeEventListener('resize', this.handlers.onResize);
        document.removeEventListener('mousemove', this.handlers.onMouseMove);
        document.removeEventListener('mouseup', this.handlers.onMouseUp);
        document.removeEventListener('touchmove', this.handlers.onTouchMove);
        document.removeEventListener('touchend', this.handlers.onTouchEnd);
    },

    updateContainerWidth() {
        this.containerWidth = this.$el.offsetWidth;
    },

    startDrag(e) {
        this.isDragging = true;
        this.$el.focus();
        this.onDrag(e);
    },

    stopDrag() {
        this.isDragging = false;
    },

    onDrag(e) {
        if (!this.isDragging) return;

        e.preventDefault();

        const rect = this.$el.getBoundingClientRect();
        let clientX;

        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
        } else if (e.changedTouches && e.changedTouches.length > 0) {
            clientX = e.changedTouches[0].clientX;
        } else {
            clientX = e.clientX;
        }

        const x = clientX - rect.left;
        const percentage = (x / rect.width) * 100;

        this.pos = Math.max(0, Math.min(100, percentage));
    },
}));
