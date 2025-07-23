<?php

namespace App\Services;

use App\Models\UniformType;

class UniformInventoryService
{
    public function decrementStock(string $uniformType, string $size, int $quantity, int $courseId): bool
    {
        return UniformType::decrementStock($uniformType, $size, $quantity, $courseId);
    }

    public function restoreStock(string $uniformType, string $size, int $quantity, int $courseId): void
    {
        UniformType::incrementStock($uniformType, $size, $quantity, $courseId);
    }

    public function getAvailableStock(string $uniformType, string $size, int $courseId): int
    {
        return UniformType::getAvailableStock($uniformType, $size, $courseId);
    }
}