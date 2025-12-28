<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffTuDashboardController;
use App\Http\Controllers\KaprodiDashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratAktifController;
use App\Http\Controllers\SuratCutiController;
use App\Http\Controllers\SuratPersetujuanAktifController;
use App\Http\Controllers\SuratKeteranganAktifController;


use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // ---- SURAT AKTIF ----
    Route::get('/ajukan-surat/aktif', [SuratAktifController::class, 'create'])
        ->name('surat.aktif.create');

    Route::post('/ajukan-surat/aktif', [SuratAktifController::class, 'store'])
        ->name('surat.aktif.store');


    // ---- SURAT CUTI ----
    Route::get('/ajukan-surat/cuti', [SuratCutiController::class, 'create'])
        ->name('surat.cuti.create');

    Route::post('/ajukan-surat/cuti', [SuratCutiController::class, 'store'])
        ->name('surat.cuti.store');

    // ---- SURAT PERSETUJUAN AKTIF ----
    Route::get('/ajukan-surat/persetujuan-aktif', [SuratPersetujuanAktifController::class, 'create'])
        ->name('surat.persetujuan-aktif.create');

    Route::post('/ajukan-surat/persetujuan-aktif', [SuratPersetujuanAktifController::class, 'store'])
        ->name('surat.persetujuan-aktif.store');

    // ---- SURAT keterangan AKTIF ----
    Route::get('/ajukan-surat/keterangan-aktif', [SuratKeteranganAktifController::class, 'create'])
    ->name('surat.keterangan-aktif.create');

    Route::post('/ajukan-surat/keterangan-aktif', [SuratKeteranganAktifController::class, 'store'])
        ->name('surat.keterangan-aktif.store');


    // GENERATE SURAT (opsional)
    Route::post('/generate-surat', [SuratController::class, 'generateSuratAppsScript'])
        ->name('surat.generate');

    // LOG & PREVIEW
    Route::get('/log-surat', [SuratController::class, 'logSurat'])->name('surat.log');
    Route::get('/log-surat/{id}', [SuratController::class, 'preview'])->name('surat.preview');

    // DELETE
    Route::delete('/log-surat/{id}/delete', [SuratController::class, 'delete'])
        ->name('surat.delete');

    // DOWNLOAD
    Route::get('/surat/download/{id}', [SuratController::class, 'downloadPdf'])
        ->name('surat.download');


    Route::get('/dashboard-tu', [StaffTuDashboardController::class, 'index'])
        ->name('dashboard.tu');

    Route::get('/dashboard-tu/surat/{id}', [StaffTuDashboardController::class, 'preview'])
        ->name('staff-tu.preview');

    Route::post(
        '/staff-tu/surat/{id}/arsipkan',
        [StaffTuDashboardController::class, 'arsipkan']
    )->name('staff-tu.arsipkan');


    Route::post(
        '/dashboard-tu/surat/{id}/approve',
        [StaffTuDashboardController::class, 'approve']
    )->name('staff-tu.approve');

    Route::post(
        '/dashboard-tu/surat/{id}/reject',
        [StaffTuDashboardController::class, 'reject']
    )->name('staff-tu.reject');



    Route::delete('/log-surat/{id}/delete', [SuratController::class, 'delete'])
        ->name('surat.delete');

    Route::get('/surat/download/{id}', [SuratController::class, 'downloadPdf'])
        ->name('surat.download');



    Route::get('/kaprodi/dashboard',
        [KaprodiDashboardController::class, 'index']
    )->name('kaprodi.dashboard');

    Route::get('/kaprodi/preview/{id}',
        [KaprodiDashboardController::class, 'preview']
    )->name('kaprodi.preview');

    Route::post('/kaprodi/approve/{id}',
        [KaprodiDashboardController::class, 'approve']
    )->name('kaprodi.approve');

    Route::post('/kaprodi/reject/{id}',
        [KaprodiDashboardController::class, 'reject']
    )->name('kaprodi.reject');
});

// Route::middleware(['auth', 'role:TU'])->group(function () {


// });
