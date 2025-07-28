<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspecialidadController;




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
    Route::resource('/admin/medicamentos','App\Http\Controllers\MedicamentoController')->parameters(['medicamentos' => 'codigoMedicamento']);
    Route::put('/admin/medicamentos','App\Http\Controllers\MedicamentoController@update');
    Route::patch('/usuarios/{id}/cambiar-estado', [App\Http\Controllers\UserController::class, 'cambiarEstado'])->name('usuarios.cambiarEstado');
    Route::patch('/medicamentos/{codigoMedicamento}/cambiar-estado', [App\Http\Controllers\MedicamentoController::class, 'cambiarEstado'])->name('medicamento.cambiarEstado');
   Route::resource('especialidades', App\Http\Controllers\EspecialidadController::class)->only(['index', 'create', 'store', 'edit', 'update']);


require __DIR__.'/auth.php';
