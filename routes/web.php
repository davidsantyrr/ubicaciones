<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UbicacionController;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return redirect('/');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('ensure.auth')->group(function(){
    Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
    Route::post('/ubicaciones', [UbicacionController::class, 'store'])->name('ubicaciones.store');
    Route::put('/ubicaciones/{id}', [UbicacionController::class, 'update'])->name('ubicaciones.update');
    Route::delete('/ubicaciones/{id}', [UbicacionController::class, 'destroy'])->name('ubicaciones.destroy');
});