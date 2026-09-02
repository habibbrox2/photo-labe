<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'service_id',
        'quantity', 'deadline', 'requirements', 'quoted_price',
        'admin_notes', 'status', 'priority',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'deadline' => 'date',
            'quoted_price' => 'decimal:2',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function items() { return $this->hasMany(QuoteItem::class); }
    public function files() { return $this->hasMany(QuoteFile::class); }
    public function order() { return $this->hasOne(Order::class); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeRecent($query) { return $query->latest(); }
}
