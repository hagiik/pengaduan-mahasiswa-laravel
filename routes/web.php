<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanController;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified','check.user.status'])
    ->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::prefix('pengaduan')->middleware(['auth','verified','check.profile.status'])->group(function () {
    Route::get('/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/', [PengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/', [PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/{slug}', [PengaduanController::class, 'show'])->name('pengaduan.show'); // Gunakan slug
    Route::get('/{slug}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit'); // Gunakan slug
    Route::put('/{slug}', [PengaduanController::class, 'update'])->name('pengaduan.update'); // Gunakan slug
    Route::delete('/{slug}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy'); // Gunakan slug
});

require __DIR__.'/auth.php';
