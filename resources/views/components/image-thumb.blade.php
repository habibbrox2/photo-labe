@props(['src', 'index', 'alt' => '', 'title' => 'Click to view the full image'])

{{--
    Clickable thumbnail for admin lists. Must sit inside an element declaring
    `x-data="portfolioGallery([...urls])"` — the click only tells that lightbox
    which image in the page-wide list to open.
--}}
<button type="button"
        x-on:click="openLightbox({{ (int) $index }})"
        title="{{ $title }}"
        aria-label="View full image: {{ $alt !== '' ? $alt : 'image' }}"
        class="group relative block overflow-hidden rounded-lg border border-gray-200 bg-gray-50 cursor-zoom-in focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1">
    {{-- max-w-none: Tailwind's preflight caps every img at max-width:100%, which lets a
         narrow table column squash the thumbnail instead of letting the table scroll. --}}
    <img loading="lazy" decoding="async" src="{{ $src }}" alt="{{ $alt }}"
         class="h-12 w-16 max-w-none object-cover transition-transform duration-300 group-hover:scale-105">
    <span class="absolute inset-0 flex items-center justify-center bg-gray-900/0 transition-colors duration-200 group-hover:bg-gray-900/50" aria-hidden="true">
        <x-icon name="eye" class="h-4 w-4 text-white opacity-0 transition-opacity duration-200 group-hover:opacity-100" />
    </span>
</button>
