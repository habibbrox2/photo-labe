<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = ['quote_id', 'name', 'description', 'quantity', 'unit_price'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_price' => 'decimal:2'];
    }

    public function quote() { return $this->belongsTo(Quote::class); }
}
