import './bootstrap';
import './before-after';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

window.Alpine = Alpine;
Alpine.start();

// Alpine marks transitions that get interrupted mid-animation (e.g. closing a
// dropdown or mobile menu while it is still animating open) with an internal
// { isFromCancelledTransition: true } rejection. These are expected and benign;
// swallow them so they don't surface as "Uncaught (in promise)" console noise.
window.addEventListener('unhandledrejection', (event) => {
    if (event.reason && event.reason.isFromCancelledTransition) {
        event.preventDefault();
    }
});
