<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'order_id', 'ticket_number', 'subject', 'priority', 'status',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->ticket_number)) {
                $model->ticket_number = 'TKT-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function messages() { return $this->hasMany(SupportTicketMessage::class, 'ticket_id'); }

    public function scopeOpen($query) { return $query->where('status', 'open'); }
    public function scopeRecent($query) { return $query->latest(); }
}
