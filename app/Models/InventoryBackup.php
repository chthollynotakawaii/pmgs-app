<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class InventoryBackup extends Model
{
    use HasFactory, Notifiable;
    public $timestamps = false; // Using recorded_at instead of created_at/updated_at
    protected $fillable = [
        'serial_number',
        'qty',
        'unit',
        'description',
        'remarks',
        'brand_id',
        'model_id',
        'category_id',
        'department_id',
        'supplier_id',
        'location_id',
        'status',
    ];
}
