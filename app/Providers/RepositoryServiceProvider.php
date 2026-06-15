<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ReportRepositoryInterface;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\AssignmentRepositoryInterface;
use App\Repositories\AssignmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
