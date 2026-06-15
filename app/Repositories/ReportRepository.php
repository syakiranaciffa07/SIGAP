<?php

namespace App\Repositories;

use App\Models\Report;

class ReportRepository implements ReportRepositoryInterface
{
    public function getAll()
    {
        return Report::with('user')->orderBy('priority_score', 'desc')->get();
    }

    public function getById($id)
    {
        return Report::with(['user', 'assignments.officer', 'upvotes'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Report::create($data);
    }

    public function update($id, array $data)
    {
        $report = Report::findOrFail($id);
        $report->update($data);
        return $report;
    }

    public function getPending()
    {
        return Report::with('user')->where('status', 'masuk')->orderBy('created_at', 'desc')->get();
    }

    public function getAssigned()
    {
        return Report::with(['user', 'assignments.officer'])->whereIn('status', ['diverifikasi', 'ditugaskan', 'ditangani', 'selesai'])->get();
    }

    public function getByOfficer($officerId)
    {
        return Report::whereHas('assignments', function ($query) use ($officerId) {
            $query->where('officer_id', $officerId);
        })->with('user')->orderBy('priority_score', 'desc')->get();
    }

    public function incrementUpvote($reportId)
    {
        $report = Report::findOrFail($reportId);
        $report->increment('upvote_count');
        return $report;
    }

    public function decrementUpvote($reportId)
    {
        $report = Report::findOrFail($reportId);
        if ($report->upvote_count > 0) {
            $report->decrement('upvote_count');
        }
        return $report;
    }

    public function getStats()
    {
        return [
            'total' => Report::count(),
            'masuk' => Report::where('status', 'masuk')->count(),
            'pending' => Report::where('status', 'masuk')->count(), // Compatibility
            'diverifikasi' => Report::where('status', 'diverifikasi')->count(),
            'verified' => Report::where('status', 'diverifikasi')->count(), // Compatibility
            'ditugaskan' => Report::where('status', 'ditugaskan')->count(),
            'ditangani' => Report::where('status', 'ditangani')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'flood_count' => Report::where('type', 'banjir')->count(),
            'infra_count' => Report::where('type', 'infrastruktur')->count(),
        ];
    }
}
