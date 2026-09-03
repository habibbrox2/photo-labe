@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'center'])

<div @class(['max-w-2xl', 'mx-auto text-center' => $align === 'center', 'text-left' => $align !== 'center'])>
    @if($eyebrow)
        <span class="eyebrow mb-4">{{ $eyebrow }}</span>
    @endif
    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tight">{{ $title }}</h2>
    @if($subtitle)
        <p class="lead">{{ $subtitle }}</p>
    @endif
</div>
