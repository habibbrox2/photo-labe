@props(['page'])
@php($blocks = $page->blocks ?? [])
@foreach($blocks as $block)
    @php($data = $block['data'] ?? [])
    @if($block['type'] === 'hero')
        <section class="page-hero-light relative overflow-hidden">
            @if(!empty($data['image']))<img src="{{ asset('storage/'.$data['image']) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-10" loading="lazy">@endif
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24"><div class="max-w-3xl">@if(!empty($data['eyebrow']))<span class="eyebrow">{{ $data['eyebrow'] }}</span>@endif<h1 class="mt-5 text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900">{{ $data['title'] ?? $page->title }}</h1>@if(!empty($data['subtitle']))<p class="lead mt-5">{{ $data['subtitle'] }}</p>@endif</div></div>
        </section>
    @elseif($block['type'] === 'rich_text')
        <section class="py-14 lg:py-20 bg-white"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg max-w-none prose-headings:font-extrabold prose-headings:tracking-tight prose-p:text-gray-600">@if(!empty($data['heading']))<h2>{{ $data['heading'] }}</h2>@endif{!! $data['body'] ?? '' !!}</div></section>
    @elseif($block['type'] === 'image' && !empty($data['path']))
        <figure class="py-10 bg-white"><div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8"><img src="{{ asset('storage/'.$data['path']) }}" alt="{{ $data['alt'] ?? '' }}" class="w-full rounded-2xl border border-surface-200" loading="lazy">@if(!empty($data['caption']))<figcaption class="mt-3 text-sm text-gray-500">{{ $data['caption'] }}</figcaption>@endif</div></figure>
    @elseif($block['type'] === 'cta')
        <section class="py-14 bg-surface-50"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center surface-card p-8 lg:p-12"><h2 class="text-3xl font-extrabold tracking-tight">{{ $data['heading'] ?? '' }}</h2>@if(!empty($data['text']))<p class="mt-3 text-gray-500">{{ $data['text'] }}</p>@endif
            @if(!empty($data['url']) && !empty($data['label']))<a href="{{ $data['url'] }}" class="btn btn-primary mt-6">{{ $data['label'] }}</a>@endif</div></section>
    @elseif($block['type'] === 'stats' && !empty($data['items']))
        <section class="py-12 bg-surface-50"><div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6">@foreach($data['items'] as $item)<div class="text-center"><p class="text-3xl font-extrabold text-gray-900">{{ $item['value'] }}</p><p class="mt-1 text-sm text-gray-500">{{ $item['label'] }}</p></div>@endforeach</div></section>
    @elseif($block['type'] === 'faq' && !empty($data['items']))
        <section class="py-16 bg-white" x-data="{ open: null }"><div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">@foreach($data['items'] as $i => $item)<div class="border border-surface-200 rounded-xl"><button @click="open=open==={{ $i }}?null:{{ $i }}" class="w-full flex justify-between text-left p-5 font-semibold"><span>{{ $item['question'] }}</span><span aria-hidden="true">+</span></button><p x-show="open==={{ $i }}" x-collapse class="px-5 pb-5 text-gray-500">{{ $item['answer'] }}</p></div>@endforeach</div></section>
    @elseif($block['type'] === 'collection')
        @include('frontend.partials.collection-block', ['data' => $data])
    @endif
@endforeach
