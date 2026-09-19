<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'read_at',
        'read_by',
        'replied_at',
        'replied_by',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function markRead(User $user): void
    {
        if ($this->status !== 'read') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
                'read_by' => $user->id,
            ]);
        }
    }

    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function isReplied(): bool
    {
        return $this->replied_at !== null;
    }
}
