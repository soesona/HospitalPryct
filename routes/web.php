<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\CitaController;


Route::get('/', function () {
    return view('welcome');
})->name('home');


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
   
   // Grupo de rutas para Doctores
    Route::prefix('doctores')->name('doctores.')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('index');
    Route::get('/obtener-datos/{id}', [DoctorController::class, 'obtenerDatos'])->name('obtener-datos');
    Route::post('/crear-registro', [DoctorController::class, 'guardarRegistroCrear'])->name('crear-registro');
    Route::post('/guardar-registro', [DoctorController::class, 'guardarRegistroEditar'])->name('guardar-registro'); // editar
});

   // Mostrar listado de citas y el modal para agendar (GET)
Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');

// Guardar cita (POST)
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');

// Rutas AJAX para cargar datos dinámicos
Route::get('/doctores-por-especialidad/{id}', [CitaController::class, 'getDoctoresPorEspecialidad']);
Route::get('/horas-disponibles/{codigoDoctor}/{fecha}', [CitaController::class, 'getHorasDisponibles']);


    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::put('/usuarios/asignar-rol/{usuario}', [UserController::class, 'asignarRol'])->name('usuarios.asignarRol');
    Route::put('/usuarios/{codigoUsuario}', [UserController::class, 'actualizar'])->name('usuarios.actualizar');

require __DIR__.'/auth.php';
