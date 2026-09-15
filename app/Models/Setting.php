<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'key', 'value', 'type'];

    protected static function booted(): void
    {
        // CacheService caches every setting for an hour, so a saved setting has to
        // drop that cache or edits would stay invisible until it expired.
        $flush = fn () => Cache::forget('site_settings');

        static::saved($flush);
        static::deleted($flush);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value): static
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function group(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)->get();
    }
}
