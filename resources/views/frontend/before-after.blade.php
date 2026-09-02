@extends('layouts.app')
@section('title', 'Before & After')
@section('content')
<section class="bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Before & After</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Drag the slider to see the transformation. The quality speaks for itself.</p>
    </div>
</section>
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($items->count())
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($items as $item)
                    <div>
                        <x-before-after
                            before="{{ asset('storage/' . $item->before_image) }}"
                            after="{{ asset('storage/' . $item->after_image) }}"
                            title="{{ $item->title }}"
                        />
                        @if($item->description)
                            <p class="mt-3 text-sm text-gray-600 text-center">{{ $item->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-400 py-20">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-lg font-semibold text-gray-600 mb-2">No examples yet</p>
                <p class="text-sm">Check back soon for before & after editing examples.</p>
            </div>
        @endif
    </div>
</section>
@endsection
