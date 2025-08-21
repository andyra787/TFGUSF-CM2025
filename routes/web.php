<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\TipoConsultaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VerCitasController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Rutas para búsquedas dinámicas
Route::get('/buscar-pacientes-ajax', [PacientesController::class, 'buscarPacientesAjax']);
Route::get('/buscar-citas-ajax', [CitaController::class, 'buscarCitasAjax']);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/citas');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('ver-citas', [VerCitasController::class, 'index']);

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ----- RUTAS DE CONSULTAS -----
    Route::get('/consultas', [ConsultaController::class, 'index'])->middleware(['auth', 'verified'])->name('consultas.index');
    Route::post('/consultas', [ConsultaController::class, 'store'])->middleware(['auth', 'verified'])->name('consultas.store');
    Route::get('/consultas/ver/{id}', [ConsultaController::class, 'show'])->name('consultas.show');
    Route::get('/consultas/editar/{id}', [ConsultaController::class, 'edit'])->middleware(['auth', 'verified'])->name('consultas.edit');
    Route::put('/consultas/actualizar/{id}', [ConsultaController::class, 'update'])->middleware(['auth', 'verified'])->name('consultas.update');
    Route::delete('/consultas/delete/{id}', [ConsultaController::class, 'destroy'])->middleware(['auth', 'verified'])->name('consultas.delete');

    // ---- CITAS -----
    route::get('/citas', [CitaController::class, 'index'])->name('citas.index')->middleware(['auth', 'verified']);
    route::post('/citas', [CitaController::class, 'store'])->name('citas.save')->middleware(['auth', 'verified']);
    route::get('/citas/ver', [CitaController::class, 'show'])->middleware(['auth', 'verified']);
    route::get('/citas/listar', [CitaController::class, 'listar'])->middleware(['auth', 'verified']);
    route::get('/citas/editar/{id}', [CitaController::class, 'edit'])->middleware(['auth', 'verified']);
    route::get('/citas/atendido/{id}', [CitaController::class, 'atendido'])->middleware(['auth', 'verified']);
    route::post('/citas/actualizar', [CitaController::class, 'update'])->middleware(['auth', 'verified']);

    // ---- CALENDARIO -----
    route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index')->middleware(['auth', 'verified']);
    route::get('/calendario/ver', [CalendarioController::class, 'show'])->middleware(['auth', 'verified']);

    // ---- PACIENTES -----
    Route::get('/pacientes', [PacientesController::class, 'index'])->middleware(['auth', 'verified'])->name('pacientes.index');
    Route::get('/registro-paciente', [PacientesController::class, 'nuevo'])->middleware(['auth', 'verified'])->name('registro-paciente');
    Route::get('/pacientes/edit/{id}', [PacientesController::class, 'edit'])->middleware(['auth', 'verified'])->name('pacientes.edit');
    Route::post('/guardar-paciente', [PacientesController::class, 'crear'])->middleware(['auth', 'verified'])->name('guardar-paciente');
    Route::post('/pacientes/edit/{id}', [PacientesController::class, 'actualizar'])->middleware(['auth', 'verified'])->name('pacientes.actualizar');
    Route::delete('/pacientes/delete/{id}', [PacientesController::class, 'eliminar'])->middleware(['auth', 'verified'])->name('pacientes.delete');
    Route::get('/registro-paciente-e', [PacientesController::class, 'error'])->name('registro-paciente-e');
    Route::get('/obtener-ciudades/{departamento}', [PacientesController::class, 'obtenerCiudades']);

    //reportes
    Route::get('/reportes', [PacientesController::class, 'reportes'])->name('pacientes.reportes')->middleware(['auth', 'verified']);
    Route::get('/reportes/citas', [CitaController::class, 'reportes'])->name('reportes.citas')->middleware(['auth', 'verified']);
    Route::post('/reportes/citas', [CitaController::class, 'generarReporte'])->name('reportes.citas.generar')->middleware(['auth', 'verified']);
    Route::get('/reportes/pacientes', [PacientesController::class, 'reportes'])->name('reportes.pacientes')->middleware(['auth', 'verified']);
    Route::post('/reportes/pacientes', [PacientesController::class, 'generarReporte'])->name('reportes.pacientes.generar')->middleware(['auth', 'verified']);
});

// ----- MIDLEWARE POR ROL DE USUARIO -----
Route::group(['middleware' => ['role:admin']], function () {

    // ---- SALAS -----
    Route::resource('salas', SalaController::class)->middleware(['auth', 'verified']);

    // ---- ESPECIALIDADES -----
    Route::resource('especialidades', EspecialidadController::class)->middleware(['auth', 'verified']);

    // ---- MEDICOS -----
    Route::resource('medicos', MedicoController::class)->middleware(['auth', 'verified']);
    // ---- DEPARTAMENTOS -----
    route::resource('departamentos', DepartamentoController::class)->middleware(['auth', 'verified']);

    // ---- TIPOS DE CONSULTAS -----
    Route::resource('tipos-consulta', TipoConsultaController::class)->middleware(['auth', 'verified']);

    // ---- CIUDADES -----
    route::resource('ciudades', CiudadController::class)->middleware(['auth', 'verified']);

    // ---- USUARIOS -----
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index')->middleware(['auth', 'verified']);
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store')->middleware(['auth', 'verified']);
    Route::post('/usuarios/update', [UsuarioController::class, 'update'])->name('usuarios.update')->middleware(['auth', 'verified']);
    Route::post('/usuarios/pass-change', [UsuarioController::class, 'passChange'])->name('usuarios.passChange')->middleware(['auth', 'verified']);
    Route::post('/usuarios/cambiarEstado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.cambiarEstado')->middleware(['auth', 'verified']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activitylog.index');
});

require __DIR__.'/auth.php';
