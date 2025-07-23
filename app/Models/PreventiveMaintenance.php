<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreventiveMaintenance extends Model
{
    protected $fillable = ['inventory_record_ids', 'maintenance_type', 'scheduled_date', 'remarks'];

    protected $casts = [
        'inventory_record_ids' => 'array',
        'scheduled_date' => 'date',
    ];

    public function inventoryRecords()
    {
        return $this->belongsToMany(InventoryRecord::class, 'preventive_maintenances', 'id', 'inventory_record_ids');
    }
    public function setMaintenanceTypeAttribute($value)
    {
        $this->attributes['maintenance_type'] = strtoupper($value);
    }
}
