<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_number', 'user_id', 'product_id', 'payment_id',
        'amount', 'currency', 'status', 'completed_at',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'completed_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->purchase_number)) {
                $model->purchase_number = 'PUR-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function payment() { return $this->belongsTo(Payment::class); }
}
