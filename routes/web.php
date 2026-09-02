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
use App\Http\Controllers\CargaAcademicaController;
use App\Http\Controllers\AlumnosAdminController;
use App\Http\Controllers\AlumnoPortalController;
use App\Http\Controllers\AlumnoMateriasController;
use App\Http\Controllers\AlumnoPagosController;
use App\Http\Controllers\ValidarPagoController;
use App\Http\Controllers\titulacionController;
use App\Http\Controllers\DocenteTitulacionController;
use App\Http\Controllers\CoodinacionCargaController;
use App\Http\Controllers\CoodinacionProyectoController;
use App\Http\Controllers\JuradosController;
use App\Http\Controllers\ColoresController;
use App\Http\Controllers\CoordinacionDashboardController;
use App\Http\Controllers\UsuarioPreferenciaController;


Route::get('/', function () {
    return view('cpanel/home/landing');
});

Route::get('/login', [LoginController::class, 'redirectByRol'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->patch('/usuario/preferencia-tema', [UsuarioPreferenciaController::class, 'actualizarTema'])->name('usuario.actualizar-tema');

Route::middleware(['auth', 'rol:administrador'])->group(function () {
    Route::resource('/admon/usuarios', UsuarioController::class)->names('usuarios');
    Route::patch('/admin/usuarios/toggle/{id}', [UsuarioController::class, 'toggleStatus'])
        ->name('usuarios.toggle-status');
    Route::resource('/admon/docentes', DocenteController::class)->names('docentes');
    Route::resource('/admon/alumnos', AlumnosAdminController::class)->names('AdAlumnos');
    Route::patch('/admon/alumnos/toggle/{id}', [AlumnosAdminController::class, 'store'])->name('admin.alumnos.toggle-status');
    Route::get('/configuracion/colores', [ColoresController::class, 'index'])->name('admin.colores.index');
    Route::post('/configuracion/colores', [ColoresController::class, 'store'])->name('admin.colores.store');
    Route::patch('/admon/usuarios/{id}/password', [UsuarioController::class, 'updatePassword'])->name('usuarios.update-password');
});

Route::middleware(['auth', 'rol:Estudiante'])->group(function () {
    Route::resource('/alumno/', AlumnoPortalController::class)->names('indexalumnos');
    Route::resource('/alumno/materias', AlumnoMateriasController::class)->names('indexmaterias');
    Route::resource('/alumno/pagos', AlumnoPagosController::class)->names('alumnoPagos');
    Route::post('/alumno/pagos/reportar', [AlumnoPagosController::class, 'store'])->name('alumno.pagos.store');
    Route::resource('/alumno/titulacion', titulacionController::class)->names('titulacion');
    Route::post('/alumno/titulacion/agregar-integrante', [titulacionController::class, 'agregarCompanero'])->name('titulacion.agregar-companero');
    Route::post('/alumno/titulacion/asignar-asesor', [titulacionController::class, 'asignarAsesor'])->name('titulacion.asignar-asesor');
    Route::get('/alumno/titulacion/repositorio/{proyectoId}', [titulacionController::class, 'repositorio'])->name('titulacion.repositorio');
    Route::post('/alumno/titulacion/repositorio/guardar-entregable', [titulacionController::class, 'guardarEntregable'])->name('titulacion.guardar-entregable');
    Route::post('/alumno/titulacion/repositorio/guardar-video', [titulacionController::class, 'guardarVideo'])->name('titulacion.guardar-video');
});

Route::middleware(['auth', 'rol:Control Escolar'])->group(function () {
    Route::resource('/ce/materias', MateriaController::class)->names('materias');
    Route::resource('/ce/alumnos', AlumnoController::class)->names('alumnos');
    Route::get('/control-escolar/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::post('/control-escolar/alumnos/asignar', [AlumnoController::class, 'asignarGrupo'])->name('alumnos.asignar-grupo');
    Route::resource('/ce/grupos', GrupoController::class)->names('grupos');
    Route::resource('/ce/carga-academica', CargaAcademicaController::class)->names('cargas');
});

Route::middleware(['auth', 'rol:Docente'])->group(function () {
    Route::resource('/docente/index', DashboardDocenteController::class)->names('dashboardDocente');
    Route::get('/docente/asistencia/tomar/{cargaId}', [AsistenciaController::class, 'tomarAsistencia'])->name('asistencia.tomar');  
    Route::post('/docente/asistencia/guardar/{cargaId}', [AsistenciaController::class, 'guardarAsistencia'])->name('asistencia.guardar');
    Route::get('/titulacion/asesorados', [DocenteTitulacionController::class, 'index'])->name('docente.titulacion.asesorados');
    Route::post('/titulacion/evaluar', [DocenteTitulacionController::class, 'evaluar'])->name('docente.titulacion.evaluar');
    Route::post('/titulacion/votar-jurado', [DocenteTitulacionController::class, 'votarJurado'])->name('docente.titulacion.votar-jurado');
});

Route::middleware(['auth', 'rol:Coordinador'])->group(function(){
    Route::resource('/coordinador/cargas', CoodinacionCargaController::class)->names('coordinador.cargas');
    Route::resource('/coordinador/proyectos', CoodinacionProyectoController::class)->names('coordinador.proyectos');
    Route::get('/jurados/{carrera}', [JuradosController::class, 'carrera'])->name('coordinador.jurados.carrera');
    Route::post('/jurados/guardar', [JuradosController::class, 'guardar'])->name('coordinador.jurados.guardar');
    Route::post('/jurados/guardar-todos', [JuradosController::class, 'guardarTodos'])->name('coordinador.jurados.guardar-todos');
    Route::get('/dashboard', [CoordinacionDashboardController::class, 'index'])->name('coordinador.dashboard');
});



Route::resource('/finanzas/pagos', ValidarPagoController::class)->names('contador.pagos');
Route::get('/finanzas/pagos/{id}/revisar', [ValidarPagoController::class, 'revisar'])->name('contador.pagos.revisar');
Route::post('/finanzas/pagos/{id}/validar', [ValidarPagoController::class, 'validar'])->name('contador.pagos.validar');

Route::get('/orientacion/asistencias', [AsistenciaController::class, 'reporteCritico'])->name('asistencias.criticas');
Route::post('/orientacion/asistencias/alerta', [AsistenciaController::class, 'enviarAlertaTutor'])->name('asistencias.alerta-tutor');