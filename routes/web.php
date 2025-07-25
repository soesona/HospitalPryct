<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;




Route::get('/', function () {
    return view('welcome');
})->name('home');

// En routes/web.php
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    
   
    
});
 Route::resource('/usuarios','App\Http\Controllers\UserController');
 Route::resource('/enfermedad','App\Http\Controllers\EnfermedadController');
  Route::resource('/consulta','App\Http\Controllers\ConsultasController');
Route::resource('/admin/medicamentos','App\Http\Controllers\MedicamentoController');
Route::patch('/usuarios/{id}/cambiar-estado', [App\Http\Controllers\UserController::class, 'cambiarEstado'])->name('usuarios.cambiarEstado');

require __DIR__.'/auth.php';
