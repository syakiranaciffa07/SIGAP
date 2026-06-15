<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Repositories\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class MasyarakatController extends Controller
{
    protected $reportService;
    protected $reportRepo;

    public function __construct(ReportService $reportService, ReportRepositoryInterface $reportRepo)
    {
        $this->reportService = $reportService;
        $this->reportRepo = $reportRepo;
    }

    /**
     * Show public dashboard.
     */
    public function dashboard()
    {
        $reports = $this->reportRepo->getAll();
        $myReports = Auth::check() 
            ? $reports->where('user_id', Auth::id()) 
            : collect();
            
        return view('masyarakat.dashboard', compact('reports', 'myReports'));
    }

    /**
     * Show report creation form.
     */
    public function createReport()
    {
        return view('masyarakat.create_report');
    }

    /**
     * Store a report.
     */
    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:banjir,infrastruktur',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:2048', // Max 2MB
            'water_level' => 'nullable|integer|min:0',
        ]);

        if ($validated['type'] === 'infrastruktur') {
            $validated['water_level'] = 0;
        }

        $validated['user_id'] = Auth::id();
        $photoFile = $request->file('photo');

        $this->reportService->createReport($validated, $photoFile);

        return redirect()->route('masyarakat.dashboard')->with('success', 'Laporan berhasil dikirim dan akan segera ditinjau oleh Admin.');
    }

    /**
     * Upvote a report.
     */
    public function upvote($id)
    {
        $this->reportService->toggleUpvote($id, Auth::id());
        return back()->with('success', 'Status upvote laporan berhasil diperbarui.');
    }
}
