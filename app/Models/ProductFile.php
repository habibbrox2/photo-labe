<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'file_name', 'file_path', 'file_type', 'file_size'];

    protected function casts(): array
    {
        return ['file_size' => 'integer'];
    }

    public function product() { return $this->belongsTo(Product::class); }
}
