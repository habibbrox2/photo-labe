{{--
    Usage:
    <x-flash />                                renders success + error from session
    <x-flash type="warning" :message="..." />  explicit message
    <x-flash type="info" :message="..." dismissible />
--}}
@props([
    'type' => null,
    'message' => null,
    'dismissible' => false,
])

@php
$types = [
    'success' => [
        'display' => session('success'),
        'icon' => 'check',
        'wrapper' => 'bg-emerald-50/90 border-emerald-200 text-emerald-800',
        'iconBg' => 'bg-emerald-500/15',
        'iconColor' => 'text-emerald-600',
    ],
    'error' => [
        'display' => session('error'),
        'icon' => 'warning',
        'wrapper' => 'bg-red-50/90 border-red-200 text-red-800',
        'iconBg' => 'bg-red-500/15',
        'iconColor' => 'text-red-600',
    ],
    'warning' => [
        'display' => session('warning'),
        'icon' => 'warning',
        'wrapper' => 'bg-amber-50/90 border-amber-200 text-amber-800',
        'iconBg' => 'bg-amber-500/15',
        'iconColor' => 'text-amber-600',
    ],
    'info' => [
        'display' => session('info'),
        'icon' => 'info',
        'wrapper' => 'bg-sky-50/90 border-sky-200 text-sky-800',
        'iconBg' => 'bg-sky-500/15',
        'iconColor' => 'text-sky-600',
    ],
];

$render = [];
if ($message !== null) {
    $render[] = ['type' => $type ?? 'info', 'text' => $message];
} else {
    foreach (['success', 'error', 'warning', 'info'] as $t) {
        if ($types[$t]['display']) {
            $render[] = ['type' => $t, 'text' => $types[$t]['display']];
        }
    }
}
@endphp

@if(count($render))
    @foreach($render as $flash)
    @php $style = $types[$flash['type']] @endphp
    <div class="surface-card {{ $style['wrapper'] }} flex items-start gap-3 px-5 py-4" role="alert" {{ $attributes }}>
        <span class="w-8 h-8 shrink-0 rounded-full {{ $style['iconBg'] }} flex items-center justify-center">
            <x-icon :name="$style['icon']" class="w-4 h-4 {{ $style['iconColor'] }}" />
        </span>
        <div class="flex-1 pt-1 text-sm font-medium min-w-0">{{ $flash['text'] }}</div>
        @if($dismissible)
        <button type="button" @click="$el.closest('[role=alert]').remove()"
            class="shrink-0 pt-1.5 -mr-1 text-current/50 hover:text-current transition-colors" aria-label="Dismiss">
            <x-icon name="x" class="w-4 h-4" />
        </button>
        @endif
    </div>
    @endforeach
@endif
