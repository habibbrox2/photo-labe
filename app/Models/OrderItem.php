<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'name', 'description', 'quantity', 'unit_price', 'total_price'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_price' => 'decimal:2', 'total_price' => 'decimal:2'];
    }

    public function order() { return $this->belongsTo(Order::class); }
}
