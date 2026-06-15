<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getByRole($role);
    public function getById($id);
    public function getAll();
}
