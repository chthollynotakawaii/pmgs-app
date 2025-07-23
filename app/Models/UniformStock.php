<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UniformStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'type',
        'size',
        'quantity',
        'batch_date',
        'batch_number'
    ];

    protected $casts = [
        'batch_date' => 'date',
        'quantity' => 'integer'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public static function decrementStock(string $type, string $size, int $quantity, int $courseId): bool
    {
        $stock = self::where([
            'course_id' => $courseId,
            'type' => $type,
            'size' => $size,
        ])
        ->orderBy('batch_date')
        ->first();

        if (!$stock || $stock->quantity < $quantity) {
            return false;
        }

        $stock->decrement('quantity', $quantity);
        return true;
    }

    public static function incrementStock(string $type, string $size, int $quantity, int $courseId): void
    {
        $latestBatch = self::where([
            'course_id' => $courseId,
            'type' => $type,
            'size' => $size,
        ])
        ->latest('batch_date')
        ->first();

        if ($latestBatch) {
            $latestBatch->increment('quantity', $quantity);
        } else {
            self::create([
                'course_id' => $courseId,
                'type' => $type,
                'size' => $size,
                'quantity' => $quantity,
                'batch_date' => now(),
                'batch_number' => 'BATCH-' . now()->format('YmdHis')
            ]);
        }
    }

    public static function getAvailableStock(string $type, string $size, int $courseId): int
    {
        return self::where([
            'course_id' => $courseId,
            'type' => $type,
            'size' => $size,
        ])->sum('quantity');
    }
}