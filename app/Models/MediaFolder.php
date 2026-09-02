<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaFolder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'slug'];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function parent() { return $this->belongsTo(MediaFolder::class, 'parent_id'); }
    public function children() { return $this->hasMany(MediaFolder::class, 'parent_id'); }
    public function media() { return $this->hasMany(Media::class, 'folder_id'); }
}
