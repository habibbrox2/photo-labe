@props(['status'])

@php
$map = [
    'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
    'reviewing' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
    'quoted' => 'bg-primary-50 text-primary-700 ring-primary-600/10',
    'accepted' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
    'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
    'processing' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
    'quality_check' => 'bg-violet-50 text-violet-700 ring-violet-600/10',
    'revision' => 'bg-orange-50 text-orange-700 ring-orange-600/10',
    'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
    'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
    'confirmed' => 'bg-sky-50 text-sky-700 ring-sky-600/10',
    'expired' => 'bg-gray-100 text-gray-600 ring-gray-500/10',
    'failed' => 'bg-red-50 text-red-700 ring-red-600/10',
    'refunded' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
    'rejected' => 'bg-red-50 text-red-700 ring-red-600/10',
    'cancelled' => 'bg-red-50 text-red-700 ring-red-600/10',
    'converted' => 'bg-accent-50 text-accent-700 ring-accent-600/10',
    'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
    'inactive' => 'bg-gray-100 text-gray-600 ring-gray-500/10',
    'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
    'draft' => 'bg-gray-100 text-gray-600 ring-gray-500/10',
];
$class = $map[$status] ?? 'bg-gray-100 text-gray-700 ring-gray-500/10';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset $class"]) }}>
    {{ $slot }}
    @if($slot->isEmpty())
        {{ ucfirst(str_replace('_', ' ', $status)) }}
    @endif
</span>
