@extends('admin.layouts.app')
@section('page-title', 'Design System Styleguide')

@section('content')
<p class="text-sm text-gray-500 mb-8">The PhotoLabe design system — warm neutral + amber/gold, light &amp; airy. All values live in <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">resources/css/app.css</code> and <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">resources/views/components/</code>.</p>

{{-- Color palette --}}
<section class="mb-12">
    <h2 class="text-lg font-bold text-gray-900 mb-1">Color tokens</h2>
    <p class="text-sm text-gray-500 mb-5">Three families drive the whole system: <strong>primary</strong> (warm espresso ink for actions), <strong>accent</strong> (amber/gold for highlights &amp; marketing CTAs), <strong>surface/gray</strong> (warm paper neutrals).</p>
    <div class="grid lg:grid-cols-3 gap-6">
        @php
            $palettes = [
                ['name' => 'primary', 'steps' => ['50','100','200','300','400','500','600','700','800','900','950']],
                ['name' => 'accent',  'steps' => ['50','100','200','300','400','500','600','700','800','900','950']],
                ['name' => 'surface', 'steps' => ['50','100','200','300','800','900','950']],
            ];
        @endphp
        @foreach($palettes as $palette)
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">{{ $palette['name'] }}</h3>
            <div class="space-y-1.5">
                @foreach($palette['steps'] as $step)
                @php
                    $hex = match ($palette['name']) {
                        'primary' => ['50'=>'#faf8f6','100'=>'#f2eee8','200'=>'#e4dbd1','300'=>'#d0bfae','400'=>'#b29a83','500'=>'#967c63','600'=>'#5d5145','700'=>'#4c4137','800'=>'#3a312a','900'=>'#2a241f','950'=>'#1a1613'][$step],
                        'accent' => ['50'=>'#fffbeb','100'=>'#fef3c7','200'=>'#fde68a','300'=>'#fcd34d','400'=>'#fbbf24','500'=>'#f59e0b','600'=>'#d97706','700'=>'#b45309','800'=>'#92400e','900'=>'#78350f','950'=>'#451a03'][$step],
                        default => ['50'=>'#fbfaf8','100'=>'#f5f3ef','200'=>'#e8e4dc','300'=>'#d8d1c6','800'=>'#2e2924','900'=>'#221e1a','950'=>'#161310'][$step],
                    };
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-14 h-9 rounded-lg border border-black/5" style="background-color: {{ $hex }}"></span>
                    <span class="text-xs font-medium text-gray-600 w-10">-{{ $step }}</span>
                    <span class="text-xs text-gray-400 font-mono">{{ $hex }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- Typography --}}
<section class="mb-12">
    <h2 class="text-lg font-bold text-gray-900 mb-5">Typography</h2>
    <div class="rounded-2xl border border-gray-200 bg-white p-7 space-y-4">
        <div><p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Display / Hero</p><p class="text-4xl font-extrabold tracking-tight text-gray-900">Extrabold tracking-tight</p></div>
        <div><p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Section heading</p><p class="text-3xl font-extrabold tracking-tight text-gray-900">What sets us <em class="italic text-accent-600">apart</em></p></div>
        <div><p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Eyebrow pill</p><p><span class="eyebrow">Our Services</span></p></div>
        <div><p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Lead body</p><p class="text-lg text-gray-500 max-w-2xl">Retouching, background removal and creative design — delivered by specialists with unlimited free revisions.</p></div>
        <div><p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Body</p><p class="text-sm text-gray-600 max-w-2xl">Standard copy sits at 14–16px in gray-600 on white or surface backgrounds. Headlines use Plus Jakarta Sans extrabold with tight tracking.</p></div>
    </div>
</section>

{{-- Buttons --}}
<section class="mb-12">
    <h2 class="text-lg font-bold text-gray-900 mb-5">Buttons</h2>
    <div class="rounded-2xl border border-gray-200 bg-white p-7 space-y-6">
        <div class="flex flex-wrap items-center gap-3">
            <button class="btn btn-primary">Primary</button>
            <button class="btn btn-secondary">Secondary</button>
            <button class="btn btn-ghost">Ghost</button>
            <button class="btn btn-danger">Danger</button>
            <button class="btn btn-gradient">Gradient CTA</button>
            <button class="btn btn-primary" disabled>Disabled</button>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button class="btn btn-primary btn-sm">Small</button>
            <button class="btn btn-primary btn-md">Medium</button>
            <button class="btn btn-primary btn-lg">Large</button>
        </div>
        <div class="border-t border-gray-100 pt-5 flex flex-wrap gap-2">
            <span class="px-3.5 py-1.5 rounded-full bg-accent-100 text-accent-700 text-xs font-semibold uppercase tracking-wider">Filter chip · active</span>
            <span class="px-3.5 py-1.5 rounded-full bg-white border border-gray-200 text-gray-600 text-xs font-semibold">Filter chip · idle</span>
            <span class="px-3 py-1 rounded-full bg-gray-900 text-white text-[11px] font-bold uppercase tracking-wider">Dark chip</span>
        </div>
    </div>
</section>

{{-- Form controls --}}
<section class="mb-12">
    <h2 class="text-lg font-bold text-gray-900 mb-5">Form controls</h2>
    <div class="rounded-2xl border border-gray-200 bg-white p-7 grid sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Text input</label>
            <input type="text" placeholder="Placeholder text" class="form-control-modern">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Select</label>
            <select class="form-control-modern"><option>Option one</option><option>Option two</option></select>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Textarea</label>
            <textarea rows="3" placeholder="Longer message…" class="form-control-modern resize-none"></textarea>
        </div>
    </div>
</section>

{{-- Components --}}
<section class="mb-12">
    <h2 class="text-lg font-bold text-gray-900 mb-5">Reusable components</h2>
    <div class="rounded-2xl border border-gray-200 bg-white p-7 space-y-8">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-4">Status badges — <code>&lt;x-status-badge&gt;</code></p>
            <div class="flex flex-wrap gap-2">
                @foreach(['pending','quoted','accepted','in_progress','completed','revision','rejected','paid','published','draft'] as $status)
                <x-status-badge :status="$status" />
                @endforeach
            </div>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-4">Icons — <code>&lt;x-icon name="…"&gt;</code></p>
            <div class="flex flex-wrap gap-3 text-gray-600">
                @foreach(['check','chevron-down','arrow-right','clock','star','shield','cart','user','mail','phone','bell','search','trash','pencil','settings','download','image','document','credit-card','refresh','sparkles','map-pin','calendar','external-link','send','chat'] as $icon)
                <span class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center" title="{{ $icon }}"><x-icon name="{{ $icon }}" class="w-5 h-5" /></span>
                @endforeach
            </div>
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-4">Empty state — <code>&lt;x-empty-state&gt;</code></p>
            <x-empty-state icon="document" title="Nothing here yet" description="This is how empty lists present themselves, with an optional action." />
        </div>
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-4">Section header — <code>&lt;x-section-header&gt;</code></p>
            <x-section-header eyebrow="Section eyebrow" title="Section heading" subtitle="An optional supporting line under the heading." />
        </div>
    </div>
</section>
@endsection
