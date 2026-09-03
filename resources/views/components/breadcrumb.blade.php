{{--
    Usage:
    <x-breadcrumb :items="[
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Services', 'url' => '/services'],
        ['name' => 'Photo Retouching']
    ]" />
--}}
@props(['items' => []])

@if($items->count() || count($items))
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center flex-wrap gap-2 text-sm">
            @foreach($items as $index => $item)
                <li class="flex items-center">
                    @if($index > 0)
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    @endif
                    
                    @if(isset($item['url']) && $index < $items->count() - 1)
                        <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                            {{ $item['name'] }}
                        </a>
                    @else
                        <span class="text-gray-900 font-medium">{{ $item['name'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
