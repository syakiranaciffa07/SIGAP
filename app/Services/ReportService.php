<?php

namespace App\Services;

use App\Repositories\ReportRepositoryInterface;
use App\Models\Upvote;
use App\Models\Report;
use App\Services\PriorityService;
use App\Services\DuplicateDetectionService;
use App\Services\WeatherService;
use App\Services\NotificationService;
use Carbon\Carbon;

class ReportService
{
    protected $reportRepo;
    protected $priorityService;
    protected $duplicateDetectionService;
    protected $weatherService;
    protected $notificationService;

    public function __construct(
        ReportRepositoryInterface $reportRepo,
        PriorityService $priorityService,
        DuplicateDetectionService $duplicateDetectionService,
        WeatherService $weatherService,
        NotificationService $notificationService
    ) {
        $this->reportRepo = $reportRepo;
        $this->priorityService = $priorityService;
        $this->duplicateDetectionService = $duplicateDetectionService;
        $this->weatherService = $weatherService;
        $this->notificationService = $notificationService;
    }

    /**
     * Create a new report, automatically checking for duplicates via DuplicateDetectionService,
     * and calculating initial priority score via PriorityService + WeatherService.
     */
    public function createReport(array $data, $photoFile = null)
    {
        if ($photoFile) {
            $path = $photoFile->store('reports', 'public');
            $data['photo'] = $path;
        }

        $data['status'] = 'masuk';
        $data['duplicate_count'] = 0;
        $data['upvote_count'] = 0;

        // Get weather score from WeatherService
        $weatherScore = $this->weatherService->getWeatherScore();

        // Calculate initial priority score
        $data['priority_score'] = (int) $this->priorityService->calculatePriority(
            (float) ($data['water_level'] ?? 0),
            0,
            0,
            $weatherScore
        );

        // Detect duplicates: 100m radius, 30 minutes via DuplicateDetectionService
        $duplicate = $this->duplicateDetectionService->checkDuplicate(
            (float) $data['latitude'],
            (float) $data['longitude'],
            Carbon::now()
        );

        if ($duplicate) {
            // Recalculate priority score for the original duplicate report
            $duplicate->priority_score = (int) $this->priorityService->calculatePriority(
                (float) $duplicate->water_level,
                $duplicate->upvote_count,
                $duplicate->duplicate_count,
                $weatherScore
            );
            $duplicate->save();
        }

        $report = $this->reportRepo->create($data);

        // Notify admin about new report
        $this->notificationService->sendToAdmin("Laporan baru diterima: " . $data['title']);

        return $report;
    }

    /**
     * Toggle a user's upvote, modifying upvote count and recalculating priority score.
     */
    public function toggleUpvote($reportId, $userId)
    {
        $existingUpvote = Upvote::where('report_id', $reportId)
            ->where('user_id', $userId)
            ->first();

        $report = Report::findOrFail($reportId);

        if ($existingUpvote) {
            $existingUpvote->delete();
            $report->decrement('upvote_count');
        } else {
            Upvote::create([
                'report_id' => $reportId,
                'user_id' => $userId
            ]);
            $report->increment('upvote_count');
        }

        // Refresh and recalculate priority score
        $report->refresh();
        $weatherScore = $this->weatherService->getWeatherScore();
        $report->priority_score = (int) $this->priorityService->calculatePriority(
            (float) $report->water_level,
            $report->upvote_count,
            $report->duplicate_count,
            $weatherScore
        );
        $report->save();

        return $report;
    }

    public function verifyReport($reportId)
    {
        $report = $this->reportRepo->update($reportId, ['status' => 'diverifikasi']);
        $this->notificationService->sendToReporter("Laporan Anda telah diverifikasi oleh Admin BPBD.");
        return $report;
    }

    public function updateStatus($reportId, $status)
    {
        return $this->reportRepo->update($reportId, ['status' => $status]);
    }
}
