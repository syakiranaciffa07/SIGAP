<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReportRepositoryInterface;
use App\Models\Report;

class PimpinanController extends Controller
{
    protected $reportRepo;

    public function __construct(ReportRepositoryInterface $reportRepo)
    {
        $this->reportRepo = $reportRepo;
    }

    /**
     * Show Pimpinan dashboard with data formatted for charts.
     */
    public function dashboard()
    {
        $stats = $this->reportRepo->getStats();
        $reports = $this->reportRepo->getAll();

        $statusChartData = [
            'masuk' => $stats['masuk'],
            'diverifikasi' => $stats['diverifikasi'],
            'ditugaskan' => $stats['ditugaskan'],
            'ditangani' => $stats['ditangani'],
            'selesai' => $stats['selesai'],
        ];

        $typeChartData = [
            'banjir' => $stats['flood_count'],
            'infrastruktur' => $stats['infra_count'],
        ];

        // Monthly trends (grouped by year and month)
        $monthlyData = Report::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            DATE_FORMAT(created_at,'%b %Y') as month_label,
            COUNT(*) as total
        ")
        ->groupBy('year','month','month_label')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        $monthlyTrends = [];
        foreach ($monthlyData as $row) {
            $monthlyTrends[$row->month_label] = $row->total;
        }

        return view('pimpinan.dashboard', compact('stats', 'reports', 'statusChartData', 'typeChartData', 'monthlyTrends'));
    }

    /**
     * Export report to Excel (via compatible UTF-8 CSV).
     */
    public function exportExcel()
    {
        $reports = $this->reportRepo->getAll();
        $csvHeader = ['ID Laporan', 'Kategori', 'Judul Laporan', 'Deskripsi', 'Latitude', 'Longitude', 'Tinggi Air (cm)', 'Status', 'Skor Prioritas', 'Jumlah Upvote', 'Jumlah Duplikat', 'Waktu Pengaduan'];

        $callback = function() use ($reports, $csvHeader) {
            $file = fopen('php://output', 'w');
            
            // Output UTF-8 BOM for Microsoft Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $csvHeader, ';');

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->id,
                    $report->type === 'banjir' ? 'Banjir' : 'Kerusakan Infrastruktur',
                    $report->title,
                    $report->description,
                    $report->latitude,
                    $report->longitude,
                    $report->water_level,
                    ucfirst($report->status),
                    $report->priority_score,
                    $report->upvote_count,
                    $report->duplicate_count,
                    $report->created_at->format('d-m-Y H:i'),
                ], ';');
            }
            fclose($file);
        };

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=sigapbanjir-laporan-" . date('Ymd-His') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Return printable report page for PDF export.
     */
    public function printPdf()
    {
        $reports = $this->reportRepo->getAll();
        $stats = $this->reportRepo->getStats();
        return view('pimpinan.report_pdf', compact('reports', 'stats'));
    }
}
