<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteFile extends Model
{
    use HasFactory;

    protected $fillable = ['quote_id', 'original_name', 'stored_name', 'file_path', 'mime_type', 'file_size'];

    protected function casts(): array
    {
        return ['file_size' => 'integer'];
    }

    public function quote() { return $this->belongsTo(Quote::class); }
}
