<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'folder_id', 'user_id', 'original_name', 'file_name',
        'file_path', 'mime_type', 'file_size', 'width', 'height',
        'alt_text', 'title',
    ];

    protected function casts(): array
    {
        return ['file_size' => 'integer', 'width' => 'integer', 'height' => 'integer'];
    }

    public function folder() { return $this->belongsTo(MediaFolder::class, 'folder_id'); }
    public function user() { return $this->belongsTo(User::class); }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }
}
