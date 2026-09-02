<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'original_name', 'stored_name', 'file_path',
        'mime_type', 'file_size', 'type',
    ];

    protected function casts(): array
    {
        return ['file_size' => 'integer'];
    }

    public function order() { return $this->belongsTo(Order::class); }

    public function isInput(): bool { return $this->type === 'input'; }
    public function isOutput(): bool { return $this->type === 'output'; }
}
