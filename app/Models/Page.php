<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    /** Key of the page that backs the hand-written /about route. */
    public const SYSTEM_ABOUT = 'about';

    protected $fillable = [
        'title', 'slug', 'system_key', 'eyebrow', 'subtitle', 'content', 'template',
        'featured_image', 'status', 'seo_title', 'seo_description',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function scopeActive($query) { return $query->where('status', 'published'); }

    public function scopeSystemKey($query, string $key) { return $query->where('system_key', $key); }

    /**
     * Pages that a hand-written route serves. Their slug can change freely because
     * the route is bound to the key, but the page itself should stay in place.
     */
    public function isSystemPage(): bool
    {
        return filled($this->system_key);
    }

    /** Public URL of a system page, or null for ordinary pages. */
    public function publicUrl(): ?string
    {
        return match ($this->system_key) {
            self::SYSTEM_ABOUT => route('about'),
            default => null,
        };
    }
}
