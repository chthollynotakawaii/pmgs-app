<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BorrowingLog extends Model
{   
    use Notifiable, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'inventory_record_id',
        'user_id',
        'custom_borrower',
        'location_id',
        'quantity',
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
            if ($item && $item->qty >= $log->quantity) {
                $item->decrement('qty', $log->quantity);
            }
            // Do nothing if not enough quantity — assume form handles validation
        });

        static::updating(function ($log) {
            $item = InventoryRecord::find($log->inventory_record_id);

            if (! $item) {
                return;
            }

            // If quantity changed, adjust inventory accordingly
            if ($log->isDirty('quantity')) {
                $originalQty = $log->getOriginal('quantity');
                $diff = $log->quantity - $originalQty;

                if ($diff > 0 && $item->qty >= $diff) {
                    $item->decrement('qty', $diff);
                } elseif ($diff < 0) {
                    $item->increment('qty', abs($diff));
                }
                // If not enough stock, skip silently
            }

            // If remarks changed to true (returned), add back the quantity
            if ($log->isDirty('remarks') && $log->remarks == true) {
                $item->increment('qty', $log->quantity);
            }

            // If remarks changed from true to false (undo return), subtract again
            if (
                $log->isDirty('remarks') &&
                $log->getOriginal('remarks') == true &&
                $log->remarks == false &&
                $item->qty >= $log->quantity
            ) {
                $item->decrement('qty', $log->quantity);
            }
        });

        static::deleting(function ($log) {
            if (! $log->remarks) {
                $item = InventoryRecord::find($log->inventory_record_id);
                $item?->increment('qty', $log->quantity);
            }
        });
    }
}
