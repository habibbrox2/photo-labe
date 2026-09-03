@props(['icon' => 'folder', 'title' => 'Nothing here yet', 'description' => null])

<div class="flex flex-col items-center justify-center px-6 py-14 text-center">
    <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-400 flex items-center justify-center mb-4">
        <x-icon :name="$icon" class="w-7 h-7" />
    </div>
    <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if($description)
        <p class="mt-1.5 text-sm text-gray-400 max-w-sm">{{ $description }}</p>
    @endif
    @if(!$slot->isEmpty())
        <div class="mt-5">{{ $slot }}</div>
    @endif
</div>
