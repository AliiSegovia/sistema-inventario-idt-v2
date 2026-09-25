<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProductManager;
use App\Http\Livewire\OperatorManager;
use App\Http\Livewire\MovementManager;
use App\Http\Livewire\DashboardComponent;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    Route::get('/productos', ProductManager::class)->name('productos');
    Route::get('/operarios', OperatorManager::class)->name('operarios');
    Route::get('/movimientos', MovementManager::class)->name('movimientos');
});