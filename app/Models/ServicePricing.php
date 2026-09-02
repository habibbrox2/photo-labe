<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePricing extends Model
{
    use HasFactory;

    protected $table = 'service_pricing';

    protected $fillable = [
        'service_id',
        'plan_name',
        'price',
        'description',
        'features',
        'is_popular',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
