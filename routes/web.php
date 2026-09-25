<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductManager;
use App\Livewire\OperatorManager;
use App\Livewire\MovementManager;

// Redirigir la raíz del sitio directamente al login o dashboard al entrar
Route::get('/', function () {
    return redirect('/login');
});

// Ruta principal protegida por Jetstream y autenticación
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas para tus módulos gestionados con Livewire
    Route::get('/productos', ProductManager::class)->name('productos');
    Route::get('/operarios', OperatorManager::class)->name('operarios');
    Route::get('/movimientos', MovementManager::class)->name('movimientos');
});