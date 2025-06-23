<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingLog extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'inventory_record_id',
        'user_id',
        'location_id', // corrected here
        'returned_at',
        'remarks',
    ];

    public function inventoryRecord(): BelongsTo
    {
        return $this->belongsTo(InventoryRecord::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
    protected static function booted()
    {
        static::creating(function ($log) {
            $item = InventoryRecord::find($log->inventory_record_id);
            if ($item && $item->qty > 0) {
                $item->decrement('qty');
            } else {
                throw new \Exception("Item unavailable for borrowing.");
            }
        });

        static::updating(function ($log) {
            if ($log->isDirty('returned_at') && $log->returned_at !== null) {
                $item = InventoryRecord::find($log->inventory_record_id);
                $item?->increment('qty');
            }
        });

        static::deleting(function ($log) {
            if (is_null($log->returned_at)) {
                $item = InventoryRecord::find($log->inventory_record_id);
                $item?->increment('qty');
            }
        });
    }

}
