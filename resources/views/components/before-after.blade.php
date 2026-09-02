{{--
    Usage:
    <x-before-after
        before="{{ asset('storage/path/to/before.jpg') }}"
        after="{{ asset('storage/path/to/after.jpg') }}"
        title="Portrait Retouching"
    />
--}}
@props([
    'before',
    'after',
    'title' => '',
    'aspect' => 'aspect-square',
])

<div
    class="group relative rounded-2xl overflow-hidden bg-gray-100 {{ $aspect }} select-none"
    x-data="beforeAfterSlider()"
    x-on:keydown.left="pos = Math.max(0, pos - 2)"
    x-on:keydown.right="pos = Math.min(100, pos + 2)"
    x-on:keydown.home="pos = 0"
    x-on:keydown.end="pos = 100"
    tabindex="0"
    role="slider"
    aria-label="Before and after comparison{{ $title ? ': ' . $title : '' }}"
    aria-valuemin="0"
    aria-valuemax="100"
    x-bind:aria-valuenow="Math.round(pos)"
>

    {{-- After Image (full background) --}}
    <img
        src="{{ $after }}"
        alt="{{ $title ? $title . ' - After' : 'After' }}"
        class="absolute inset-0 w-full h-full object-cover"
        loading="lazy"
    >

    {{-- Before Image (clipped) --}}
    <div class="absolute inset-0 overflow-hidden" x-bind:style="'width: ' + pos + '%'">
        <img
            src="{{ $before }}"
            alt="{{ $title ? $title . ' - Before' : 'Before' }}"
            class="absolute inset-0 w-full h-full object-cover"
            x-bind:style="'min-width: 100%; width: ' + containerWidth + 'px'"
            loading="lazy"
        >
    </div>

    {{-- Slider Line --}}
    <div
        class="absolute top-0 bottom-0 w-0.5 bg-white shadow-[0_0_8px_rgba(0,0,0,0.3)] z-10"
        x-bind:style="'left: ' + pos + '%'"
        aria-hidden="true"
    ></div>

    {{-- Slider Handle --}}
    <div
        class="absolute top-0 bottom-0 cursor-ew-resize z-20"
        x-bind:style="'left: calc(' + pos + '% - 16px)'"
        x-on:mousedown.prevent="startDrag($event)"
        x-on:touchstart.prevent="startDrag($event)"
        aria-hidden="true"
    >
        <div class="absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center ring-2 ring-white/50 transition-transform group-hover:scale-110">
            <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
            </svg>
        </div>
    </div>

    {{-- Labels --}}
    <div class="absolute top-3 left-3 px-2.5 py-1 bg-black/50 backdrop-blur-sm rounded-md text-white text-xs font-medium z-10 pointer-events-none">
        Before
    </div>
    <div class="absolute top-3 right-3 px-2.5 py-1 bg-black/50 backdrop-blur-sm rounded-md text-white text-xs font-medium z-10 pointer-events-none">
        After
    </div>

    {{-- Title overlay --}}
    @if($title)
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent z-10 pointer-events-none">
            <p class="text-white text-sm font-semibold">{{ $title }}</p>
        </div>
    @endif
</div>
