<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssignmentService;
use App\Repositories\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    protected $assignmentService;
    protected $reportRepo;

    public function __construct(AssignmentService $assignmentService, ReportRepositoryInterface $reportRepo)
    {
        $this->assignmentService = $assignmentService;
        $this->reportRepo = $reportRepo;
    }

    /**
     * Display the Petugas dashboard.
     */
    public function dashboard()
    {
        $officerId = Auth::id();
        $tasks = $this->reportRepo->getByOfficer($officerId);

        return view('petugas.dashboard', compact('tasks'));
    }

    /**
     * Update status of assigned tasks.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:ditangani,selesai',
        ]);

        try {
            $this->assignmentService->updateReportStatusByOfficer($id, Auth::id(), $validated['status']);
            return redirect()->route('petugas.dashboard')->with('success', 'Status penanganan laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('petugas.dashboard')->with('error', $e->getMessage());
        }
    }
}
