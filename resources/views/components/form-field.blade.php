@props(['label', 'name', 'type' => 'text', 'required' => false, 'hint' => null])

@php($error = $errors->first($name))

<div {{ $attributes->only(['class'])->merge(['class' => '']) }}>
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
        {{ $label }}@if($required) <span class="text-red-500">*</span>@endif
    </label>

    @if($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="4" placeholder="{{ $attributes->get('placeholder') }}"
            class="form-control-modern {{ $error ? '!border-red-300' : '' }}"
            @if($required) required @endif>{{ $slot }}</textarea>
    @else
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}"
            placeholder="{{ $attributes->get('placeholder') }}"
            class="form-control-modern {{ $error ? '!border-red-300' : '' }}"
            @if($required) required @endif>
    @endif

    @if($hint && !$error)
        <p class="mt-1.5 text-xs text-gray-400">{{ $hint }}</p>
    @endif
    @if($error)
        <p class="mt-1.5 text-xs text-red-600">{{ $error }}</p>
    @endif
</div>
