<?php

namespace App\Services;

use App\Repositories\AssignmentRepositoryInterface;
use App\Repositories\ReportRepositoryInterface;
use App\Services\NotificationService;
use Carbon\Carbon;

class AssignmentService
{
    protected $assignmentRepo;
    protected $reportRepo;
    protected $notificationService;

    public function __construct(
        AssignmentRepositoryInterface $assignmentRepo,
        ReportRepositoryInterface $reportRepo,
        NotificationService $notificationService
    ) {
        $this->assignmentRepo = $assignmentRepo;
        $this->reportRepo = $reportRepo;
        $this->notificationService = $notificationService;
    }

    /**
     * Assign a field officer to a report.
     */
    public function assignOfficer($reportId, $officerId)
    {
        // Remove existing assignments for this report
        $this->assignmentRepo->deleteByReport($reportId);

        // Assign the new officer
        $assignment = $this->assignmentRepo->create([
            'report_id' => $reportId,
            'officer_id' => $officerId,
            'assigned_at' => Carbon::now()
        ]);

        // Change report status to 'ditugaskan'
        $this->reportRepo->update($reportId, ['status' => 'ditugaskan']);

        // Send notifications
        $this->notificationService->sendToOfficer("Tugas penanganan baru telah ditugaskan kepada Anda.");
        $this->notificationService->sendToReporter("Laporan Anda telah ditugaskan ke petugas lapangan.");

        return $assignment;
    }

    /**
     * Allows an assigned officer to update the report's status.
     */
    public function updateReportStatusByOfficer($reportId, $officerId, $status)
    {
        $assignment = $this->assignmentRepo->getByReport($reportId);

        if (!$assignment || $assignment->officer_id != $officerId) {
            throw new \Exception('Anda tidak diberi tugas untuk menangani laporan ini.');
        }

        if (!in_array($status, ['ditangani', 'selesai'])) {
            throw new \Exception('Status tidak valid.');
        }

        $report = $this->reportRepo->update($reportId, ['status' => $status]);

        // Send notification to reporter
        $this->notificationService->sendToReporter("Laporan Anda kini berstatus: " . ucfirst($status));

        return $report;
    }
}
