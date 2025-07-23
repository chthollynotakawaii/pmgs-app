<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use App\Models\UniformStockSummary;

class UniformInventory extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'course_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (UniformInventory $inventory) {
            Log::info("Inventory saved: ID {$inventory->id}");
            $inventory->syncStockSummary();
        });

        static::deleted(function (UniformInventory $inventory) {
            Log::info("Inventory deleted: ID {$inventory->id}");
            $inventory->deleteStockSummary();
        });
    }

    public function syncStockSummary(): void
    {
        $details = is_array($this->details) ? $this->details : json_decode($this->details, true);
        if (!is_array($details)) return;

        foreach ($details as $item) {
            if (!isset($item['uniform_type'], $item['size'], $item['quantity'])) continue;

            $uniformType = trim($item['uniform_type']);
            $size = trim($item['size']);
            $total = $this->calculateTotal($uniformType, $size);

            UniformStockSummary::updateOrCreate(
                [
                    'course_id' => $this->course_id,
                    'uniform_type' => $uniformType,
                    'size' => $size,
                ],
                [
                    'total_quantity' => $total,
                    'updated_at' => now(),
                ]
            );

            Log::info('Stock summary updated', [
                'course_id' => $this->course_id,
                'uniform_type' => $uniformType,
                'size' => $size,
                'total_quantity' => $total,
            ]);
        }
    }

    public function deleteStockSummary(): void
    {
        $details = is_array($this->details) ? $this->details : json_decode($this->details, true);
        if (!is_array($details)) return;

        foreach ($details as $item) {
            if (!isset($item['uniform_type'], $item['size'])) continue;

            $uniformType = trim($item['uniform_type']);
            $size = trim($item['size']);

            UniformStockSummary::where([
                'course_id' => $this->course_id,
                'uniform_type' => $uniformType,
                'size' => $size,
            ])->delete();

            Log::info('Stock summary deleted', [
                'course_id' => $this->course_id,
                'uniform_type' => $uniformType,
                'size' => $size,
            ]);
        }
    }

    protected function calculateTotal(string $type, string $size): int
    {
        return self::where('course_id', $this->course_id)
            ->get()
            ->flatMap(function ($inv) {
                $details = is_array($inv->details) ? $inv->details : json_decode($inv->details, true);
                return is_array($details) ? $details : [];
            })
            ->filter(fn ($item) =>
                trim($item['uniform_type'] ?? '') === trim($type) &&
                trim($item['size'] ?? '') === trim($size)
            )
            ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
    }

    public static function getAvailableQuantity($courseId, $type, $size): int
    {
        $total = self::where('course_id', $courseId)
            ->get()
            ->flatMap(function ($inv) {
                $details = is_array($inv->details) ? $inv->details : json_decode($inv->details, true);
                return is_array($details) ? $details : [];
            })
            ->filter(fn ($item) =>
                trim($item['uniform_type'] ?? '') === trim($type) &&
                trim($item['size'] ?? '') === trim($size)
            )
            ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));

        $distributed = \App\Models\UniformDistribution::query()
            ->whereHas('uniformSize', fn ($q) => $q->where('course_id', $courseId))
            ->get()
            ->flatMap(fn ($dist) => is_array($dist->sizes_id) ? $dist->sizes_id : json_decode($dist->sizes_id, true))
            ->filter(fn ($item) =>
                trim($item['uniform_type'] ?? '') === trim($type) &&
                trim($item['size'] ?? '') === trim($size)
            )
            ->sum(fn ($item) => (int) ($item['quantity'] ?? 0));

        return max(0, $total - $distributed);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
