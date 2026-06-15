<?php

namespace App\Repositories;

use App\Models\Assignment;

class AssignmentRepository implements AssignmentRepositoryInterface
{
    public function create(array $data)
    {
        return Assignment::create($data);
    }

    public function getByOfficer($officerId)
    {
        return Assignment::where('officer_id', $officerId)->with('report')->get();
    }

    public function deleteByReport($reportId)
    {
        return Assignment::where('report_id', $reportId)->delete();
    }

    public function getByReport($reportId)
    {
        return Assignment::where('report_id', $reportId)->with('officer')->first();
    }
}
