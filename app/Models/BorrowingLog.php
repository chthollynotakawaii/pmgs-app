<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        });

        static::updating(function ($log) {
            $item = InventoryRecord::find($log->inventory_record_id);
            if (! $item) return;

            $originalQty = $log->getOriginal('quantity');
            $newQty = $log->quantity;

            if ($log->isDirty('quantity') && $originalQty != $newQty) {
                $diff = $newQty - $originalQty;

                if ($diff > 0 && $item->qty >= $diff) {
                    $item->decrement('qty', $diff);
                } elseif ($diff < 0) {
                    $item->increment('qty', abs($diff));
                }
            }

            if ($log->isDirty('remarks') && $log->remarks === true) {
                $item->increment('qty', $log->quantity);
            }
            if ($log->isDirty('remarks') && $log->getOriginal('remarks') === true && $log->remarks === 0 &&
                $item->qty >= $log->quantity) {
                $item->decrement('qty', $log->quantity);
            }
        });

        static::deleting(function ($log) {
            if (! $log->remarks) {
                $item = InventoryRecord::find($log->inventory_record_id);
                $item?->increment('qty', $log->quantity);
            }
        });

        static::addGlobalScope('department', function (Builder $builder) {
            $user = Auth::user();

            if ($user && $user->role !== 'admin') {
                $builder->whereHas('inventoryRecord', function ($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                });
            }
        });
    }
    
}
