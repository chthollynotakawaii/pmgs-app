<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class UniformDistribution extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'uniform_inventory_id',
        'student_id',
        'student_name',
        'department_id',
        'receipt_number',
        'quantity',
        'remarks',
    ];

    protected static function booted()
    {
        static::created(function ($distribution) {
            $inventory = UniformInventory::find($distribution->uniform_inventory_id);
            if ($inventory && $inventory->quantity >= $distribution->quantity) {
                $inventory->decrement('quantity', $distribution->quantity);
            }
        });
    }

    public function uniformInventory(): BelongsTo
    {
        return $this->belongsTo(UniformInventory::class);
    }

    public function inventoryRecord(): BelongsTo
    {
        return $this->belongsTo(InventoryRecord::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = strtoupper($value);
    }
}
