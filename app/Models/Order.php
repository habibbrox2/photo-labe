<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'quote_id', 'service_id',
        'subtotal', 'discount', 'tax', 'total', 'currency',
        'quantity', 'deadline', 'notes', 'admin_notes',
        'status', 'priority', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'quantity' => 'integer',
            'deadline' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $model) {
            if (empty($model->order_number)) {
                $model->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function quote() { return $this->belongsTo(Quote::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function files() { return $this->hasMany(OrderFile::class); }
    public function revisions() { return $this->hasMany(OrderRevision::class); }
    public function messages() { return $this->hasMany(OrderMessage::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeActive($query) { return $query->whereNotIn('status', ['completed', 'cancelled']); }
    public function scopeRecent($query) { return $query->latest(); }
}
