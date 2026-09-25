<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductManager;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta para el archivo dashboard.blade.php que tienes en views
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Tu componente Livewire de productos
    Route::get('/products', ProductManager::class)->name('products');
});