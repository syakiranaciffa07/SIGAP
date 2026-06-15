<?php

namespace App\Repositories;

interface ReportRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function getPending();
    public function getAssigned();
    public function getByOfficer($officerId);
    public function incrementUpvote($reportId);
    public function decrementUpvote($reportId);
    public function getStats();
}
