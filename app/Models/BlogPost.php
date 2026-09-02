<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt',
        'content', 'featured_image', 'is_featured', 'status',
        'published_at', 'seo_title', 'seo_description', 'views_count',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
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

    public function category() { return $this->belongsTo(BlogCategory::class, 'category_id'); }
    public function author() { return $this->belongsTo(User::class, 'author_id'); }
    public function tags() { return $this->belongsToMany(BlogTag::class, 'blog_post_tag'); }

    public function scopeActive($query) { return $query->where('status', 'published'); }
    public function scopePublished($query) { return $query->whereNotNull('published_at')->where('published_at', '<=', now()); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeRecent($query) { return $query->latest('published_at'); }
}
