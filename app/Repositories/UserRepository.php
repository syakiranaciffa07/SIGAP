<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getByRole($role)
    {
        return User::where('role', $role)->get();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function getAll()
    {
        return User::all();
    }
}
