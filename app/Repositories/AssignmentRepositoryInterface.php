<?php

namespace App\Repositories;

interface AssignmentRepositoryInterface
{
    public function create(array $data);
    public function getByOfficer($officerId);
    public function deleteByReport($reportId);
    public function getByReport($reportId);
}
