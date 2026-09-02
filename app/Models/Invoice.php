<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number', 'user_id', 'order_id', 'payment_id',
        'subtotal', 'tax', 'total', 'status', 'due_date', 'paid_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->invoice_number)) {
                $model->invoice_number = 'INV-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function payment() { return $this->belongsTo(Payment::class); }
}
