<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
    return view('auth.login');
});

// Group untuk peminjam (semua route membutuhkan auth dan role peminjam)
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/tools', function () {
        return view('peminjam.tools'); // nanti buat view daftar alat
    })->name('tools.index');
    Route::get('/loans/create', function () {
        return 'Form ajukan peminjaman (coming soon)';
    })->name('loans.create');
    Route::get('/loans', function () {
        return 'Riwayat peminjaman (coming soon)';
    })->name('loans.index');
});

// Group untuk petugas
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/loans/pending', function () {
        return 'Daftar pengajuan pending (coming soon)';
    })->name('loans.pending');
    Route::get('/returns/pending', function () {
        return 'Daftar pengembalian pending (coming soon)';
    })->name('returns.pending');
    Route::get('/reports', function () {
        return 'Halaman laporan (coming soon)';
    })->name('reports.index');
    Route::get('/violations', function () {
        return 'Kelola pelanggaran (coming soon)';
    })->name('violations.index');
    Route::get('/loans', function () {
        return 'CRUD peminjaman (coming soon)';
    })->name('loans.index');
    Route::get('/returns', function () {
        return 'CRUD pengembalian (coming soon)';
    })->name('returns.index');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('tools', ToolController::class);
    Route::resource('categories', CategoryController::class);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('tools', ToolController::class);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard
Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::middleware(['auth', 'role:petugas'])->get('/petugas', function () {
    return view('petugas.dashboard');
})->name('petugas.dashboard');

Route::middleware(['auth', 'role:peminjam'])->get('/dashboard', function () {
    return view('peminjam.dashboard');
})->name('peminjam.dashboard');

// CRUD User (hanya admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});