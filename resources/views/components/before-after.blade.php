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
    class="group relative rounded-3xl overflow-hidden bg-gray-100 {{ $aspect }} select-none shadow-xl hover:shadow-2xl transition-shadow duration-500"
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
        class="absolute top-0 bottom-0 w-0.5 bg-white shadow-[0_0_12px_rgba(0,0,0,0.4)] z-10"
        x-bind:style="'left: ' + pos + '%'"
        aria-hidden="true"
    ></div>

    {{-- Slider Handle --}}
    <div
        class="absolute top-0 bottom-0 cursor-ew-resize z-20"
        x-bind:style="'left: calc(' + pos + '% - 20px)'"
        x-on:mousedown.prevent="startDrag($event)"
        x-on:touchstart.prevent="startDrag($event)"
        aria-hidden="true"
    >
        <div class="absolute top-1/2 -translate-y-1/2 left-1/2 -translate-x-1/2 w-10 h-10 bg-white rounded-full shadow-2xl flex items-center justify-center ring-4 ring-white/50 transition-all duration-300 group-hover:scale-110 group-hover:ring-white/80">
            <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
            </svg>
        </div>
    </div>

    {{-- Labels --}}
    <div class="absolute top-4 left-4 px-3 py-1.5 bg-black/60 backdrop-blur-md rounded-xl text-white text-xs font-semibold z-10 pointer-events-none border border-white/10">
        Before
    </div>
    <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/80 backdrop-blur-md rounded-xl text-gray-900 text-xs font-semibold z-10 pointer-events-none">
        After
    </div>

    {{-- Title overlay --}}
    @if($title)
        <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/70 via-black/30 to-transparent z-10 pointer-events-none">
            <p class="text-white text-sm font-bold">{{ $title }}</p>
        </div>
    @endif
</div>
