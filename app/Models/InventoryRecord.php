<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;;

class InventoryRecord extends Model
{
    public $timestamps = true; // Using recorded_at instead of created_at/updated_at
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'qty',
        'unit',
        'description',
        'remarks',
        'brand_id',
        'model_id',
        'control_number',
        'temp_serial',
        'thumbnail',
        'category_id',
        'department_id',
        'supplier_id',
        'location_id',
        'status',
        'borrowed',
        'recorded_at',
    ];
    
    protected $casts = [
    'thumbnail' => 'string',
    ];

    // Relationships
    public function brand() { return $this->belongsTo(Brand::class); }
    public function model() { return $this->belongsTo(Models::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function location() { return $this->belongsTo(Location::class); }


    protected static function booted()
    {
        static::created(function ($record) {
            InventoryBackup::create([
                'qty' => $record->qty,
                'unit' => $record->unit,
                'description' => $record->description,
                'brand_id' => $record->brand_id,
                'model_id' => $record->model_id,
                'control_nubmer' => $record->control_nubmer,
                'temp_serial' => $record->temp_serial,
                'remarks' => $record->remarks,
                'status' => $record->status,
                'category_id' => $record->category_id,
                'department_id' => $record->department_id,
                'location_id' => $record->location_id,
                'supplier_id' => $record->supplier_id,
                'recorded_at' => now(),
            ]);
        });
        static::updated(function ($record) {
            InventoryBackup::updateOrCreate(
                ['temp_serial' => $record->temp_serial],
                [
                    'qty' => $record->qty,
                    'unit' => $record->unit,
                    'description' => $record->description,
                    'brand_id' => $record->brand_id,
                    'model_id' => $record->model_id,
                    'remarks' => $record->remarks,
                    'status' => $record->status,
                    'category_id' => $record->category_id,
                    'department_id' => $record->department_id,
                    'location_id' => $record->location_id,
                    'supplier_id' => $record->supplier_id,
                    'recorded_at' => now(),
                ]
            );
        });
        static::addGlobalScope('department', function (Builder $builder) {
            $user = Auth::user();

            // Only apply the scope if the user is not admin
            if ($user && $user->role !== 'admin') {
                $builder->where('department_id', $user->department_id);
            }
        });
        
    }
    public function inventoryRecords()
    {
        return $this->belongsToMany(InventoryRecord::class, 'preventive_maintenance_inventory_record', 'preventive_maintenance_id', 'inventory_record_id');
    }
    public function borrowingLogs()
    {
        return $this->hasMany(BorrowingLog::class);
    }
    public function setNameAttribute($value)
    {
        $this->attributes['remarks'] = strtoupper($value);
        $this->attributes['description'] = strtoupper($value);
        $this->attributes['temp_serial'] = strtoupper($value);
    }
    public function propertyRequests()
    {
        return $this->hasMany(PropertyRequest::class);
    }
}
