<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PengaduanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1/pengaduan')->group(function () {
    Route::get('/', [PengaduanController::class, 'index']); // GET semua pengaduan
    Route::get('/{id}', [PengaduanController::class, 'show']); // GET detail pengaduan
    Route::post('/', [PengaduanController::class, 'store']); // Tambah data pengaduan
    Route::put('/{id}', [PengaduanController::class, 'update']);
    Route::delete('/{id}', [PengaduanController::class, 'destroy']);

});