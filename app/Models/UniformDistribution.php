<?php

namespace App\Models;

use App\Models\UniformStockSummary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UniformDistribution extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'student_name_id',
        'sizes_id',
        'receipt_number',
    ];

    protected $casts = [
        'sizes_id' => 'array',
    ];

    public function uniformSize(): BelongsTo
    {
        return $this->belongsTo(UniformSize::class, 'student_name_id');
    }

    protected static function booted(): void
    {
        static::created(function ($distribution) {
            $distribution->adjustStock(-1);
        });

        static::updated(function ($distribution) {
            $original = $distribution->getOriginal('sizes_id') ?? [];
            $original = is_array($original) ? $original : json_decode($original, true);
            $new = $distribution->sizes_id ?? [];

            // Return old sizes first
            foreach ($original as $item) {
                UniformStockSummary::adjustStock(
                    $distribution->uniformSize->course_id,
                    $item['uniform_type'] ?? '',
                    $item['size'] ?? '',
                    (int) ($item['quantity'] ?? 0)
                );
            }

            // Then apply new sizes
            $distribution->adjustStock(-1);
        });

        static::deleted(function ($distribution) {
            $distribution->adjustStock(1);
        });
    }

    public function adjustStock(int $direction = -1): void
    {
        $sizes = $this->sizes_id ?? [];
        $courseId = $this->uniformSize->course_id ?? null;

        foreach ($sizes as $item) {
            UniformStockSummary::adjustStock(
                $courseId,
                $item['uniform_type'] ?? '',
                $item['size'] ?? '',
                $direction * (int) ($item['quantity'] ?? 0)
            );
        }
    }
}
