<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'slug', 'price', 'sale_price',
        'short_description', 'description', 'compatibility', 'features',
        'featured_image', 'download_count', 'is_featured', 'status',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'features' => 'array',
            'download_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function category() { return $this->belongsTo(ProductCategory::class, 'category_id'); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function files() { return $this->hasMany(ProductFile::class); }
    public function reviews() { return $this->morphMany(Review::class, 'reviewable'); }

    public function scopeActive($query) { return $query->where('status', 'published'); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeOrdered($query) { return $query->orderBy('title'); }

    /**
     * The price customers actually pay: the sale price when it is set AND lower
     * than the regular price, otherwise the regular price. Guards against a
     * sale price accidentally entered above the regular price.
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price !== null && (float) $this->sale_price < (float) $this->price) {
            return (float) $this->sale_price;
        }

        return (float) $this->price;
    }

    /** Whether this product is currently on sale (sale price set and lower). */
    public function getOnSaleAttribute(): bool
    {
        return $this->sale_price !== null
            && (float) $this->sale_price < (float) $this->price;
    }
}
