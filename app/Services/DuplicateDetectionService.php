<?php

namespace App\Services;

use App\Models\Report;
use Carbon\Carbon;

class DuplicateDetectionService
{
    /**
     * Check if there is a duplicate report within 100m radius and 30 minutes of the given parameters.
     * If found, increments duplicate_count of the original report.
     */
    public function checkDuplicate(
        float $latitude,
        float $longitude,
        Carbon $createdAt
    ) {
        $startTime = (clone $createdAt)->subMinutes(30);
        $endTime = (clone $createdAt)->addMinutes(30);

        // Haversine formula to calculate distance in meters
        $duplicate = Report::where('status', '!=', 'selesai')
            ->whereBetween('created_at', [$startTime, $endTime])
            ->select('*')
            ->selectRaw(
                '(6371000 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<=', 100)
            ->orderBy('distance', 'asc')
            ->first();

        if ($duplicate) {
            $duplicate->increment('duplicate_count');
            return $duplicate;
        }

        return null;
    }
}
