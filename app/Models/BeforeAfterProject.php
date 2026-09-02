<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BeforeAfterProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'service_id', 'title', 'before_image', 'after_image',
        'description', 'sort_order', 'is_featured', 'status',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'sort_order' => 'integer'];
    }

    public function category() { return $this->belongsTo(BeforeAfterCategory::class, 'category_id'); }
    public function service() { return $this->belongsTo(Service::class); }
    public function scopeActive($query) { return $query->where('status', 'published'); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
