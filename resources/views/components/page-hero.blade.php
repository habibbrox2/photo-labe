@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
    'breadcrumbs' => [], // [['label' => 'Home', 'url' => '/'], ['label' => 'Page']]
    'align' => 'center',
])

<section class="page-hero-light">
    @if(count($breadcrumbs))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb" @class(['mx-auto' => $align === 'center'])>
            <ol class="flex items-center gap-1.5 text-sm text-gray-500 @if($align === 'center') justify-center @endif">
                @foreach($breadcrumbs as $crumb)
                    @if(isset($crumb['url']) && !$loop->last)
                    <li><a href="{{ $crumb['url'] }}" class="hover:text-primary-600 transition-colors">{{ $crumb['label'] }}</a></li>
                    <li aria-hidden="true" class="text-gray-300">/</li>
                    @else
                    <li aria-current="page" class="text-gray-700 font-medium">{{ $crumb['label'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div @class(['max-w-3xl', 'mx-auto text-center' => $align === 'center'])>
            @if($eyebrow)
            <span class="eyebrow @if($align === 'center') mx-auto @endif">{{ $eyebrow }}</span>
            @endif
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">{{ $title }}</h1>
            @if($subtitle)
            <p class="mt-5 text-lg text-gray-500 leading-relaxed">{{ $subtitle }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
