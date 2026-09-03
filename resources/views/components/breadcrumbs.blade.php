@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'flex items-center gap-1.5 text-sm text-gray-500 mb-6']) }}>
    <ol class="flex flex-wrap items-center gap-1.5">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 hover:text-primary-600 transition-colors">
                <x-icon name="home" class="w-4 h-4" />
                <span class="sr-only">Admin home</span>
            </a>
        </li>
        @foreach($items as $index => $item)
            <li class="flex items-center gap-1.5">
                <x-icon name="chevron-right" class="w-3.5 h-3.5 text-gray-300" />
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="hover:text-primary-600 transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-gray-900" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
