<?php

use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;

// Halaman utama (form + tabel)
Route::get('/', [PesertaController::class, 'index'])->name('peserta.index');

// Simpan data baru
Route::post('/peserta', [PesertaController::class, 'store'])->name('peserta.store');

// Form edit
Route::get('/peserta/{peserta}/edit', [PesertaController::class, 'edit'])->name('peserta.edit');

// Update data
Route::put('/peserta/{peserta}', [PesertaController::class, 'update'])->name('peserta.update');

// Hapus data
Route::delete('/peserta/{peserta}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

// AJAX: get kabupaten/kota
Route::get('/get-kabkot', [PesertaController::class, 'getKabkot'])->name('get.kabkot');
