<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'title', 'company', 'avatar', 'content',
        'rating', 'is_featured', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['rating' => 'integer', 'is_featured' => 'boolean', 'is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeFeatured($query) { return $query->where('is_featured', true); }
    public function scopeOrdered($query) { return $query->orderBy('sort_order'); }
}
