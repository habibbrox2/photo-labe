import './bootstrap';
import './before-after';
import './portfolio-gallery';

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

/* ---------------------------------------------------------------------------
 * Confirmation dialog
 * Replaces the legacy inline `onsubmit="return confirm(...)"` handlers. Any
 * form with a `data-confirm="<message>"` attribute is intercepted and shows a
 * custom dialog before submitting. An optional `data-confirm-label` sets the
 * confirm button text (defaults to "Delete").
 * ------------------------------------------------------------------------- */
const confirmDialog = document.createElement('dialog');
confirmDialog.className =
    'm-auto w-full max-w-sm rounded-xl border border-gray-200 bg-white p-0 text-left shadow-2xl backdrop:bg-gray-900/60';
confirmDialog.innerHTML = `
    <div class="px-6 pt-6 pb-2">
        <h3 class="text-base font-semibold text-gray-900">Are you sure?</h3>
        <p class="mt-2 text-sm text-gray-500" data-confirm-message></p>
    </div>
    <div class="flex justify-end gap-2 px-6 pb-5 pt-3">
        <button type="button" data-confirm-cancel
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
        <button type="button" data-confirm-ok
            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg">Delete</button>
    </div>`;
document.body.appendChild(confirmDialog);

const confirmMessage = confirmDialog.querySelector('[data-confirm-message]');
const confirmOkButton = confirmDialog.querySelector('[data-confirm-ok]');
const confirmCancelButton = confirmDialog.querySelector('[data-confirm-cancel]');

let formPendingConfirm = null;
let confirmTrigger = null;

function closeConfirmDialog() {
    confirmDialog.close();
}

confirmDialog.addEventListener('close', () => {
    formPendingConfirm = null;
    // Return focus to whatever opened the dialog (Esc closes it too).
    if (confirmTrigger && document.contains(confirmTrigger)) {
        confirmTrigger.focus();
    }
    confirmTrigger = null;
});

confirmCancelButton.addEventListener('click', closeConfirmDialog);

confirmOkButton.addEventListener('click', () => {
    const form = formPendingConfirm;
    if (!form) return;
    closeConfirmDialog();
    // form.submit() intentionally bypasses the submit event so the dialog is
    // not re-triggered for this same submission.
    form.submit();
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;

    event.preventDefault();

    const label = form.dataset.confirmLabel || 'Delete';
    const destructive = label.toLowerCase() === 'delete';

    confirmMessage.textContent = form.dataset.confirm;
    confirmOkButton.textContent = label;
    confirmOkButton.className = destructive
        ? 'px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg'
        : 'px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg';

    formPendingConfirm = form;
    confirmTrigger = document.activeElement;

    if (!confirmDialog.open) {
        confirmDialog.showModal();
    }
});

/* ---------------------------------------------------------------------------
 * Auto-submitting controls
 * Elements flagged with `data-auto-submit` (e.g. the cart quantity <select>)
 * submit their enclosing form when their value changes.
 * ------------------------------------------------------------------------- */
document.addEventListener('change', (event) => {
    if (event.target instanceof HTMLSelectElement && event.target.hasAttribute('data-auto-submit')) {
        event.target.form?.submit();
    }
});
