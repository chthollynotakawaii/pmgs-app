<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniformInventory extends Model
{   
    protected $fillable = [
        'inventory_record_id',
        'type',
        'size',
        'quantity',
    ];

    public function inventoryRecord(): BelongsTo
    {
        return $this->belongsTo(InventoryRecord::class);
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(UniformDistribution::class);
    }
}
