/**
 * Before/After Image Slider — Alpine component.
 *
 * Interaction model:
 * - Drag anywhere on the image (pointer events — mouse, touch & pen unified)
 * - Click/tap anywhere to jump to that position
 * - Double-click to reset to center
 * - Keyboard: ← → nudge, Home/End jump
 * - Intro "sweep" animation the first time the slider scrolls into view
 *   (skipped when the user prefers reduced motion)
 */
import Alpine from 'alpinejs';

Alpine.data('beforeAfterSlider', () => ({
    pos: 50,
    isDragging: false,
    hasMoved: false,
    containerWidth: 0,
    introPlayed: false,

    init() {
        this.containerWidth = this.$el.offsetWidth;

        this.handlers = {
            onResize: () => this.updateContainerWidth(),
            onPointerMove: (e) => this.onDrag(e),
            onPointerUp: () => this.stopDrag(),
        };

        window.addEventListener('resize', this.handlers.onResize);
        document.addEventListener('pointermove', this.handlers.onPointerMove, { passive: false });
        document.addEventListener('pointerup', this.handlers.onPointerUp);
        document.addEventListener('pointercancel', this.handlers.onPointerUp);

        // Prevent native image drag ghosts while swiping.
        this.$el.querySelectorAll('img').forEach((img) => {
            img.setAttribute('draggable', 'false');
            img.addEventListener('dragstart', (e) => e.preventDefault());
        });

        // Intro sweep: animate 0 → 50 the first time the slider enters the viewport.
        if ('IntersectionObserver' in window && !this.prefersReducedMotion()) {
            this.observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !this.introPlayed) {
                    this.introPlayed = true;
                    this.observer.disconnect();
                    this.playIntro();
                }
            }, { threshold: 0.4 });
            this.observer.observe(this.$el);
        }
    },

    destroy() {
        window.removeEventListener('resize', this.handlers.onResize);
        document.removeEventListener('pointermove', this.handlers.onPointerMove);
        document.removeEventListener('pointerup', this.handlers.onPointerUp);
        document.removeEventListener('pointercancel', this.handlers.onPointerUp);
        this.observer?.disconnect();
    },

    prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    },

    playIntro() {
        const start = performance.now();
        const duration = 1400;

        const step = (now) => {
            const t = Math.min(1, (now - start) / duration);
            // easeInOutCubic
            const eased = t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
            this.pos = eased * 50;
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },

    updateContainerWidth() {
        this.containerWidth = this.$el.offsetWidth;
    },

    startDrag(e) {
        this.isDragging = true;
        this.hasMoved = true;
        this.$el.setPointerCapture?.(e.pointerId);
        this.$el.focus();
        this.moveTo(e);
    },

    stopDrag() {
        this.isDragging = false;
    },

    /** Click anywhere (not just the handle) jumps to that position. */
    onClick(e) {
        if (this.isDragging) return;
        this.moveTo(e);
    },

    onDoubleClick() {
        this.animateTo(50);
    },

    animateTo(target) {
        if (this.prefersReducedMotion()) {
            this.pos = target;
            return;
        }
        const from = this.pos;
        const start = performance.now();
        const duration = 350;

        const step = (now) => {
            const t = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
            this.pos = from + (target - from) * eased;
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    },

    moveTo(e) {
        const rect = this.$el.getBoundingClientRect();
        const x = e.clientX - rect.left;
        this.pos = Math.max(0, Math.min(100, (x / rect.width) * 100));
    },

    onDrag(e) {
        if (!this.isDragging) return;
        e.preventDefault();
        this.moveTo(e);
    },
}));
