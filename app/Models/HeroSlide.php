<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    /** Headline shown when a slide has none (single source of truth for homepage + admin preview). */
    public const DEFAULT_HEADLINE = 'Pixel-perfect photo editing for brands that <em class="italic text-accent-400">refuse to look average.</em>';

    protected $fillable = [
        'image',
        'headline',
        'caption_label',
        'caption_text',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
