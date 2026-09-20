@extends('admin.layouts.app')
@section('page-title', 'Public Site')
@section('content')
<div class="space-y-8">
    <div><span class="eyebrow">Content control</span><h2 class="mt-3 text-2xl font-extrabold">Public pages</h2><p class="mt-2 text-sm text-gray-500">Draft content stays private. Use Preview before publishing changes.</p></div>
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($keys as $key)
            @php($page = $systemPages->get($key))
            <article class="surface-card p-5">
                <div class="flex justify-between gap-3"><div><p class="text-xs uppercase tracking-wider font-bold text-gray-400">{{ ucfirst($key) }}</p><h3 class="font-bold text-gray-900 mt-1">{{ $page?->title ?? ucfirst($key) }}</h3></div><x-status-badge :status="$page?->status ?? 'draft'" /></div>
                @if($page)<p class="mt-3 text-xs text-gray-500">Updated {{ $page->updated_at->diffForHumans() }}</p><div class="mt-5 flex gap-2"><a class="btn btn-primary btn-sm" href="{{ route('admin.pages.edit', $page) }}">Edit</a><a class="btn btn-secondary btn-sm" target="_blank" href="{{ route('admin.public-site.preview', $page) }}">Preview</a><a class="text-xs font-semibold text-primary-600 self-center" target="_blank" href="{{ $page->publicUrl() }}">View live</a></div>@else<p class="mt-3 text-sm text-amber-700">Not seeded yet. Run the CMS seeder.</p>@endif
            </article>
        @endforeach
    </div>
    <div class="flex items-center justify-between"><div><h2 class="font-extrabold text-xl">Custom pages</h2><p class="text-sm text-gray-500 mt-1">Pages served at /page/slug.</p></div><a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm">Add page</a></div>
    <div class="surface-card divide-y divide-surface-200">@forelse($customPages as $page)<div class="p-4 flex flex-wrap items-center gap-3"><div class="min-w-0 flex-1"><p class="font-bold">{{ $page->title }}</p><p class="text-xs text-gray-500">/{{ $page->slug }} · updated {{ $page->updated_at->diffForHumans() }}</p></div><x-status-badge :status="$page->status" /><a class="btn btn-secondary btn-sm" href="{{ route('admin.pages.edit',$page) }}">Edit</a><a target="_blank" class="text-xs font-semibold text-primary-600" href="{{ route('admin.public-site.preview',$page) }}">Preview</a></div>@empty<x-empty-state icon="document" title="No custom pages" description="Create a page to add it to your public site." />@endforelse</div>
</div>
@endsection
