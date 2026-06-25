<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardDocenteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DocenteController;

Route::get('/', function () {
    return view('cpanel/home/landing');
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('/admon/usuarios', UsuarioController::class)->names('usuarios');
Route::patch('/admon/usuarios/toggle/{id}', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
Route::resource('/admon/docentes', DocenteController::class)->names('docentes');


Route::resource('/docente/index', DashboardDocenteController::class)->names('dashboardDocente');
Route::get('/docente/asistencia/tomar/{cargaId}', [AsistenciaController::class, 'tomarAsistencia'])->name('asistencia.tomar');  
Route::post('/docente/asistencia/guardar/{cargaId}', [AsistenciaController::class, 'guardarAsistencia'])->name('asistencia.guardar');

Route::get('/asistencia', function () {
    return view('cpanel/orientacion/asistenciadocentes');
});

Route::get('/dashboard', function () {
    return view('cpanel/orientacion/');
});

Route::get('/alerta-asistencia', function () {
    return view('cpanel/orientacion/alertaInasistencia');
});