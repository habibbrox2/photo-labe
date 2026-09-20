<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'status', 'unsubscribed_at', 'ip_address'];

    protected function casts(): array
    {
        return [
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }
}
