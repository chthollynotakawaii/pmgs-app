<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class InventoryRecord extends Model
{
    public $timestamps = true; // Using recorded_at instead of created_at/updated_at
    use HasFactory, Notifiable, SoftDeletes;

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
        'borrowed',
    ];

    // Relationships
    public function brand() { return $this->belongsTo(Brand::class); }
    public function model() { return $this->belongsTo(Models::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function location() { return $this->belongsTo(Location::class); }

    // Auto-generate serial number if not set
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->serial_number)) {
                do {
                    $serial = 'SN-' . strtoupper(Str::random(10));
                } while (self::where('serial_number', $serial)->exists());
                $record->serial_number = $serial;
            }
        });
    }

    protected static function booted()
    {
        static::created(function ($record) {
            InventoryBackup::create([
                'qty' => $record->qty,
                'unit' => $record->unit,
                'description' => $record->description,
                'brand_id' => $record->brand_id,
                'model_id' => $record->model_id,
                'serial_number' => $record->serial_number,
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
                ['serial_number' => $record->serial_number],
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
    }
    public function borrowingLogs()
    {
        return $this->hasMany(BorrowingLog::class);
    }

}
