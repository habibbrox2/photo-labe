<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number', 'user_id', 'order_id', 'amount',
        'currency', 'gateway', 'status', 'transaction_id',
        'gateway_response', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $model) {
            if (empty($model->payment_number)) {
                $model->payment_number = 'PAY-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }

    public function isPaid(): bool { return $this->status === 'paid'; }
}
