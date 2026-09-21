<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        $inputs = $request->input('settings', []);

        // Unchecked checkboxes never reach the request; force every boolean
        // setting that is missing from the payload to "0" so toggling a
        // boolean off actually persists.
        $missingBooleans = Setting::query()
            ->where('type', 'boolean')
            ->whereNotIn('key', array_keys($inputs))
            ->pluck('key');

        foreach ($missingBooleans as $key) {
            $inputs[$key] = '0';
        }

        foreach ($inputs as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
