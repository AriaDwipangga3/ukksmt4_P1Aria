<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Petugas\LoanController as PetugasLoanController;
use App\Http\Controllers\Petugas\ReturnController as PetugasReturnController;
use App\Http\Controllers\Petugas\LoanCrudController;
use App\Http\Controllers\Petugas\ReturnCrudController;
use App\Http\Controllers\Petugas\ViolationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama (login)
Route::get('/', function () {
    return view('auth.login');
});

// Route autentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =============================================
// DASHBOARD (berdasarkan role)
// =============================================
Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::middleware(['auth', 'role:petugas'])->get('/petugas', function () {
    return view('petugas.dashboard');
})->name('petugas.dashboard');

Route::middleware(['auth', 'role:peminjam'])->get('/dashboard', function () {
    return view('peminjam.dashboard');
})->name('peminjam.dashboard');

// =============================================
// ROUTE UNTUK PEMINJAM
// =============================================
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/tools', [App\Http\Controllers\Peminjam\ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/{tool}', [App\Http\Controllers\Peminjam\ToolController::class, 'show'])->name('tools.show');

    Route::get('/loans', [App\Http\Controllers\Peminjam\LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [App\Http\Controllers\Peminjam\LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}/edit', [App\Http\Controllers\Peminjam\LoanController::class, 'edit'])->name('loans.edit');
    Route::put('/loans/{loan}', [App\Http\Controllers\Peminjam\LoanController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{loan}', [App\Http\Controllers\Peminjam\LoanController::class, 'destroy'])->name('loans.destroy');

    Route::get('/loans/{loan}/return', [App\Http\Controllers\Peminjam\ReturnController::class, 'create'])->name('loans.return.create');
    Route::post('/loans/{loan}/return', [App\Http\Controllers\Peminjam\ReturnController::class, 'store'])->name('loans.return.store');
    Route::get('/returns', [App\Http\Controllers\Peminjam\ReturnController::class, 'index'])->name('returns.index');
});

// =============================================
// ROUTE UNTUK PETUGAS
// =============================================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    // Persetujuan peminjaman
    Route::get('/loans/pending', [PetugasLoanController::class, 'pending'])->name('loans.pending');
    Route::post('/loans/{loan}/approve', [PetugasLoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [PetugasLoanController::class, 'reject'])->name('loans.reject');

    // Proses pengembalian
    Route::get('/returns/pending', [PetugasReturnController::class, 'pending'])->name('returns.pending');
    Route::get('/returns/{return}/process', [PetugasReturnController::class, 'processForm'])->name('returns.process.form');
    Route::post('/returns/{return}/process', [PetugasReturnController::class, 'process'])->name('returns.process');

    // CRUD Peminjaman — pakai LoanCrudController agar nama route tidak bentrok
    Route::resource('loans_crud', LoanCrudController::class)->names([
        'index'   => 'loans_crud.index',
        'create'  => 'loans_crud.create',
        'store'   => 'loans_crud.store',
        'edit'    => 'loans_crud.edit',
        'update'  => 'loans_crud.update',
        'destroy' => 'loans_crud.destroy',
    ]);

    // CRUD Pengembalian — pakai ReturnCrudController
    Route::resource('returns_crud', ReturnCrudController::class)
        ->except(['create', 'store', 'show'])
        ->names([
            'index'   => 'returns_crud.index',
            'edit'    => 'returns_crud.edit',
            'update'  => 'returns_crud.update',
            'destroy' => 'returns_crud.destroy',
        ]);

    // Kelola Pelanggaran & Denda
    Route::resource('violations', ViolationController::class)->except(['show'])->names([
        'index'   => 'violations.index',
        'create'  => 'violations.create',
        'store'   => 'violations.store',
        'edit'    => 'violations.edit',
        'update'  => 'violations.update',
        'destroy' => 'violations.destroy',
    ]);

    // Cetak Laporan
    Route::get('/reports', [App\Http\Controllers\Petugas\ReportController::class, 'index'])->name('reports.index');
});

// =============================================
// API — ambil unit tersedia berdasarkan tool
// =============================================
Route::get('/api/get-units/{toolId}', function ($toolId) {
    return App\Models\ToolUnit::where('tool_id', $toolId)
        ->where('status', 'available')
        ->get();
});

// =============================================
// ROUTE UNTUK ADMIN
// =============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('tools', ToolController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
});