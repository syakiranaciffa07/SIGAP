<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Services\AssignmentService;
use App\Repositories\ReportRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

class AdminController extends Controller
{
    protected $reportService;
    protected $assignmentService;
    protected $reportRepo;
    protected $userRepo;

    public function __construct(
        ReportService $reportService,
        AssignmentService $assignmentService,
        ReportRepositoryInterface $reportRepo,
        UserRepositoryInterface $userRepo
    ) {
        $this->reportService = $reportService;
        $this->assignmentService = $assignmentService;
        $this->reportRepo = $reportRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display the Admin dashboard.
     */
    public function dashboard()
    {
        $stats = $this->reportRepo->getStats();
        $pendingReports = $this->reportRepo->getPending();
        $assignedReports = $this->reportRepo->getAssigned();
        $officers = $this->userRepo->getByRole('petugas');

        return view('admin.dashboard', compact('stats', 'pendingReports', 'assignedReports', 'officers'));
    }

    /**
     * Verify a report.
     */
    public function verify($id)
    {
        $this->reportService->verifyReport($id);
        return redirect()->route('admin.dashboard')->with('success', 'Laporan berhasil diverifikasi.');
    }

    /**
     * Assign a field officer to a report.
     */
    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'officer_id' => 'required|exists:users,id',
        ]);

        $this->assignmentService->assignOfficer($id, $validated['officer_id']);

        return redirect()->route('admin.dashboard')->with('success', 'Petugas lapangan berhasil ditugaskan.');
    }

    /**
     * Change report status manually.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:masuk,diverifikasi,ditugaskan,ditangani,selesai',
        ]);

        $this->reportService->updateStatus($id, $validated['status']);

        return redirect()->route('admin.dashboard')->with('success', 'Status laporan berhasil diperbarui.');
    }
}
