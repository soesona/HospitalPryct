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
use App\Http\Controllers\AdminCitaController;
use App\Http\Controllers\EnfermedadController;
use App\Http\Controllers\pacienteController;
use App\Http\Controllers\MedicamentoController;

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
    

    //reportes 
    Route::get('/usuarios/pdf', [UserController::class, 'exportarPDF'])->name('usuarios.pdf');
    Route::get('/doctores/pdf', [DoctorController::class, 'exportarPDF'])->name('doctores.pdf');
    Route::get('/enfermedades/pdf', [EnfermedadController::class, 'exportarPDF'])->name('enfermedades.pdf');
    Route::get('/citas/pdf/{estado?}', [CitaController::class, 'exportarPDF'])->name('citas.pdf');
    Route::get('/pacientes/pdf', [pacienteController::class, 'exportarPDF'])->name('pacientes.pdf');
    Route::get('/especialidades/pdf', [EspecialidadController::class, 'exportarPDF'])->name('especialidades.pdf');
    Route::get('/medicamentos/pdf', [MedicamentoController::class, 'exportarPDF'])->name('medicamentos.pdf');
});
    Route::resource('/usuarios','App\Http\Controllers\UserController');
    Route::resource('/enfermedades','App\Http\Controllers\EnfermedadController');
    Route::resource('/consultas','App\Http\Controllers\ConsultasController');
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

// Rutas para la gestión de citas por administradores
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Vista principal de gestión de citas
    Route::get('/citas', [AdminCitaController::class, 'index'])->name('citas.index');
    
    // Agendar nueva cita
    Route::post('/citas', [AdminCitaController::class, 'store'])->name('citas.store');
    
    // Cambiar estado de cita
    Route::patch('/citas/{cita}/estado', [AdminCitaController::class, 'cambiarEstado'])->name('citas.estado');
    
    // Búsqueda de pacientes (AJAX)
    Route::get('/buscar-pacientes', [AdminCitaController::class, 'buscarPacientes'])->name('buscar.pacientes');
    
    // Obtener doctores por especialidad excluyendo al paciente si es doctor (AJAX)
    Route::get('/doctores-por-especialidad/{especialidad}/{paciente}', [AdminCitaController::class, 'getDoctoresPorEspecialidad'])->name('doctores.especialidad');
    
    // Rutas adicionales opcionales
    Route::get('/citas/todas', [AdminCitaController::class, 'todasLasCitas'])->name('citas.todas');
    Route::get('/citas/estadisticas', [AdminCitaController::class, 'estadisticas'])->name('citas.estadisticas');
    Route::post('/citas/cancelar-multiples', [AdminCitaController::class, 'cancelarMultiples'])->name('citas.cancelar.multiples');
    Route::get('/citas/exportar', [AdminCitaController::class, 'exportarCSV'])->name('citas.exportar');
});


    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::resource('/pacientes','App\Http\Controllers\pacienteController');

    Route::put('/usuarios/asignar-rol/{usuario}', [UserController::class, 'asignarRol'])->name('usuarios.asignarRol');
    Route::put('/usuarios/{codigoUsuario}', [UserController::class, 'actualizar'])->name('usuarios.actualizar');

require __DIR__.'/auth.php';


