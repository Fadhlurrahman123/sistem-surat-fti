<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratAktifController;
use App\Http\Controllers\SuratCutiController;

use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::middleware('auth')->group(function () {

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
});
