/**
 * Before/After Image Slider
 * Supports mouse drag, touch drag, and keyboard navigation
 */

window.beforeAfterSlider = function () {
    return {
        pos: 50,
        isDragging: false,
        containerWidth: 0,

        init() {
            this.containerWidth = this.$el.offsetWidth;
            this.updateContainerWidth = this.updateContainerWidth.bind(this);

            window.addEventListener('resize', this.updateContainerWidth);

            // Mouse events
            document.addEventListener('mousemove', (e) => this.onDrag(e));
            document.addEventListener('mouseup', () => this.stopDrag());

            // Touch events
            document.addEventListener('touchmove', (e) => this.onDrag(e), { passive: false });
            document.addEventListener('touchend', () => this.stopDrag());
        },

        destroy() {
            window.removeEventListener('resize', this.updateContainerWidth);
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
    };
};
