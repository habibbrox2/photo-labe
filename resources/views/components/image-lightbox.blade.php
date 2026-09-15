{{--
    Full-screen image viewer for admin lists.

    Render it inside an element that declares `x-data="portfolioGallery([...urls])"`
    (see resources/js/portfolio-gallery.js). Any thumbnail on the page opens the viewer
    at its own index, and every other image on the page can be paged through from there.
--}}
@props(['label' => 'Image viewer'])

<div x-show="lightboxOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-on:keydown.escape.window="closeLightbox()"
     x-on:wheel.prevent
     class="fixed inset-0 z-[70] flex items-center justify-center bg-gray-950/95"
     style="display: none;"
     role="dialog"
     aria-modal="true"
     aria-label="{{ $label }}"
     x-cloak>
    <button type="button" x-on:click="closeLightbox()" aria-label="Close viewer"
            class="absolute right-5 top-5 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20">
        <x-icon name="x" class="h-6 w-6" />
    </button>

    <button type="button" x-show="images.length > 1" x-on:click="prevImage()" aria-label="Previous image"
            class="absolute left-4 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 sm:left-8">
        <x-icon name="chevron-left" class="h-7 w-7" />
    </button>

    <button type="button" x-show="images.length > 1" x-on:click="nextImage()" aria-label="Next image"
            class="absolute right-4 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 sm:right-8">
        <x-icon name="chevron-right" class="h-7 w-7" />
    </button>

    <div class="w-full max-w-5xl px-16 sm:px-24" x-on:click.self="closeLightbox()">
        <img loading="lazy" decoding="async" :src="images[currentIndex]" :alt="'Image ' + (currentIndex + 1)"
             class="mx-auto max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl">
        <p class="mt-5 text-center text-sm text-white/60" x-show="images.length > 1"
           x-text="(currentIndex + 1) + ' / ' + images.length"></p>
    </div>
</div>
