@extends('admin.layouts.app')
@section('page-title', 'Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    <div class="max-w-3xl space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="font-semibold text-gray-900">General Settings</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Site Name</label>
                    <input type="text" name="settings[site_name]" value="{{ $settings['site_name']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Site Email</label>
                    <input type="email" name="settings[site_email]" value="{{ $settings['site_email']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Support Email</label>
                    <input type="email" name="settings[support_email]" value="{{ $settings['support_email']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="text" name="settings[phone]" value="{{ $settings['phone']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                <textarea name="settings[address]" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ $settings['address']->value ?? '' }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
                    <input type="text" name="settings[currency]" value="{{ $settings['currency']->value ?? 'USD' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Timezone</label>
                    <input type="text" name="settings[timezone]" value="{{ $settings['timezone']->value ?? 'UTC' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO Defaults</h3>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Default Meta Title</label>
                <input type="text" name="settings[default_meta_title]" value="{{ $settings['default_meta_title']->value ?? '' }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Default Meta Description</label>
                <textarea name="settings[default_meta_description]" rows="3" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ $settings['default_meta_description']->value ?? '' }}</textarea>
            </div>
        </div>

        <div>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">Save Settings</button>
        </div>
    </div>
</form>
@endsection
