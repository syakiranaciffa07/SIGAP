<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PimpinanController;

// Public Routes (Accessible by Guests & Logged-in Users)
Route::get('/', [MasyarakatController::class, 'dashboard'])->name('masyarakat.dashboard');

// Authentication Routes (Guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Upvote route (Any logged in user can upvote)
    Route::post('/reports/{id}/upvote', [MasyarakatController::class, 'upvote'])->name('reports.upvote');

    // Masyarakat Routes (Public reporting restricted to masyarakat role)
    Route::middleware('role:masyarakat')->group(function () {
        Route::get('/reports/create', [MasyarakatController::class, 'createReport'])->name('masyarakat.create');
        Route::post('/reports', [MasyarakatController::class, 'storeReport'])->name('masyarakat.store');
    });

    // Admin BPBD Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/reports/{id}/verify', [AdminController::class, 'verify'])->name('admin.reports.verify');
        Route::post('/reports/{id}/assign', [AdminController::class, 'assign'])->name('admin.reports.assign');
        Route::post('/reports/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.reports.status');
    });

    // Petugas Lapangan Routes
    Route::middleware('role:petugas')->prefix('petugas')->group(function () {
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
        Route::post('/reports/{id}/status', [PetugasController::class, 'updateStatus'])->name('petugas.reports.status');
    });

    // Pimpinan BPBD Routes
    Route::middleware('role:pimpinan')->prefix('pimpinan')->group(function () {
        Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/export/excel', [PimpinanController::class, 'exportExcel'])->name('pimpinan.export.excel');
        Route::get('/export/pdf', [PimpinanController::class, 'printPdf'])->name('pimpinan.export.pdf');
    });
});
