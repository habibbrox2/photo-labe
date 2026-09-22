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
                    <input type="text" name="settings[site_name]" value="{{ $settings['site_name']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Site Email</label>
                    <input type="email" name="settings[site_email]" value="{{ $settings['site_email']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Support Email</label>
                    <input type="email" name="settings[support_email]" value="{{ $settings['support_email']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="text" name="settings[phone]" value="{{ $settings['phone']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                <textarea name="settings[address]" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ $settings['address']->value ?? '' }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Currency</label>
                    <select name="settings[currency]" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @foreach(\App\Support\Currency::options() as $code => $label)
                        <option value="{{ $code }}" @selected(($settings['currency']->value ?? config('currency.default')) === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Used to display all prices across the site and in emails.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Timezone</label>
                    <input type="text" name="settings[timezone]" value="{{ $settings['timezone']->value ?? 'UTC' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="font-semibold text-gray-900">Public Site</h3>

            {{-- The whole row is the toggle's label, so clicking anywhere on it flips the switch. --}}
            <label class="flex items-start justify-between gap-4 cursor-pointer">
                <div>
                    <span class="block text-sm font-semibold text-gray-700">Show prices on public service pages</span>
                    <p class="text-xs text-gray-500 mt-1">When off, visitors won't see service prices anywhere on the public site — the admin panel keeps showing them. Prices are also hidden from structured data (SEO).</p>
                </div>
                <span class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
                    <input type="checkbox" name="settings[services_show_price]" value="1"
                           @checked(\App\Models\Setting::flag('services_show_price', true))
                           class="sr-only peer">
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent-500/40 rounded-full peer peer-checked:bg-accent-500 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-transform peer-checked:after:translate-x-5"></span>
                </span>
            </label>

            <label class="flex items-start justify-between gap-4 cursor-pointer">
                <div>
                    <span class="block text-sm font-semibold text-gray-700">Show prices on public product pages</span>
                    <p class="text-xs text-gray-500 mt-1">When off, visitors won't see product prices anywhere on the public site — the admin panel keeps showing them. Prices are also hidden from structured data (SEO).</p>
                </div>
                <span class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
                    <input type="checkbox" name="settings[products_show_price]" value="1"
                           @checked(\App\Models\Setting::flag('products_show_price', true))
                           class="sr-only peer">
                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-accent-500/40 rounded-full peer peer-checked:bg-accent-500 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-transform peer-checked:after:translate-x-5"></span>
                </span>
            </label>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO Defaults</h3>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Default Meta Title</label>
                <input type="text" name="settings[default_meta_title]" value="{{ $settings['default_meta_title']->value ?? '' }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Default Meta Description</label>
                <textarea name="settings[default_meta_description]" rows="3" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ $settings['default_meta_description']->value ?? '' }}</textarea>
            </div>
        </div>

        <div>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Save Settings</button>
        </div>
    </div>
</form>
@endsection
