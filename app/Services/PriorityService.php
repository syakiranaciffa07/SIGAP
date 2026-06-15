<?php

namespace App\Services;

class PriorityService
{
    public function calculatePriority(
        float $waterLevel,
        int $upvoteCount,
        int $duplicateCount,
        float $weatherScore
    ): float {
        $score = (0.40 * $waterLevel) + (0.25 * $upvoteCount) + (0.20 * $duplicateCount) + (0.15 * $weatherScore);
        return max(0.0, $score);
    }
}
