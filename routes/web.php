<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardDocenteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\GrupoController;

Route::get('/', function () {
    return view('cpanel/home/landing');
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('/admon/usuarios', UsuarioController::class)->names('usuarios');
Route::patch('/admon/usuarios/toggle/{id}', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
Route::resource('/admon/docentes', DocenteController::class)->names('docentes');

Route::resource('/ce/materias', MateriaController::class)->names('materias');
Route::resource('/ce/alumnos', AlumnoController::class)->names('alumnos');
Route::get('/control-escolar/alumnos', [ControlEscolarAlumnoController::class, 'index'])->name('alumnos.index');
Route::post('/control-escolar/alumnos/asignar', [ControlEscolarAlumnoController::class, 'asignarGrupo'])->name('alumnos.asignar-grupo');
Route::resource('/ce/grupos', GrupoController::class)->names('grupos');


Route::resource('/docente/index', DashboardDocenteController::class)->names('dashboardDocente');
Route::get('/docente/asistencia/tomar/{cargaId}', [AsistenciaController::class, 'tomarAsistencia'])->name('asistencia.tomar');  
Route::post('/docente/asistencia/guardar/{cargaId}', [AsistenciaController::class, 'guardarAsistencia'])->name('asistencia.guardar');

