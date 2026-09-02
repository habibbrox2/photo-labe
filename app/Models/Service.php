<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'featured_image',
        'starting_price',
        'delivery_time',
        'is_featured',
        'status',
        'seo_title',
        'seo_description',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Service $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function features()
    {
        return $this->hasMany(ServiceFeature::class)->orderBy('sort_order');
    }

    public function pricing()
    {
        return $this->hasMany(ServicePricing::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
