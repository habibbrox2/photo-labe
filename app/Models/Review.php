<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'reviewable_type', 'reviewable_id', 'rating', 'comment', 'status'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function reviewable() { return $this->morphTo(); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeApproved($query) { return $query->where('status', 'approved'); }
}
