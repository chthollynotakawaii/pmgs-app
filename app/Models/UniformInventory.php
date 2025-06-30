<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UniformInventory extends Model
{   
    use Notifiable, SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'inventory_record_id',
        'details',
    ];

    public function inventoryRecord(): BelongsTo{
        return $this->belongsTo(InventoryRecord::class);
    }

    public function distributions(): HasMany{
        return $this->hasMany(UniformDistribution::class);
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

    protected $casts = [
    'details' => 'array',
    ];

}
