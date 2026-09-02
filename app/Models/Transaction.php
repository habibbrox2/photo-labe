<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'type', 'amount', 'status',
        'gateway_transaction_id', 'gateway_response',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'gateway_response' => 'array'];
    }

    public function payment() { return $this->belongsTo(Payment::class); }
}
