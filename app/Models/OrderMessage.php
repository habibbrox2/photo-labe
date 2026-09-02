<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'user_id', 'message', 'attachments', 'is_read'];

    protected function casts(): array
    {
        return ['attachments' => 'array', 'is_read' => 'boolean'];
    }

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
}
