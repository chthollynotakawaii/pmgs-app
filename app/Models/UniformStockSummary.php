<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformStockSummary extends Model
{
    protected $fillable = [
        'course_id',
        'uniform_type',
        'size',
        'total_quantity',
    ];

    public static function adjustStock(int $courseId, string $uniformType, string $size, int $quantityChange): void
    {
        if (! $courseId || ! $uniformType || ! $size || $quantityChange === 0) return;

        $summary = self::firstOrCreate([
            'course_id' => $courseId,
            'uniform_type' => $uniformType,
            'size' => $size,
        ], [
            'total_quantity' => 0,
        ]);

        $summary->increment('total_quantity', $quantityChange);
    }
}
