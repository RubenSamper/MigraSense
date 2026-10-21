<?php

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MigranaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UsuariosController::class, 'landingPage'])->name('landingPage');

Route::get('/usuarios/obtenerListadoUsuarios', [UsuariosController::class, 'obtenerListadoUsuarios'])
	->middleware('auth')
	->name('usuarios.obtenerListadoUsuarios');
Route::post('/usuarios/getListadoUsuario', [UsuariosController::class, 'getListadoUsuario'])
	->middleware('auth')
	->name('usuarios.getListadoUsuario');

Route::post('/usuarios/eliminarUsuario', [UsuariosController::class, 'eliminarUsuario'])
	->middleware('auth')
	->name('usuarios.eliminarUsuario');

Route::get('/usuarios/editarUsuario/{id}', [UsuariosController::class, 'editarUsuario'])
	->middleware('auth')
	->name('usuarios.editarUsuario');
Route::post('/usuarios/actualizarUsuario/{id}', [UsuariosController::class, 'actualizarUsuario'])
	->middleware('auth')
	->name('usuarios.actualizarUsuario');

// Gestión de médicos (solo admin, igual que el listado de pacientes de arriba).
Route::get('/usuarios/listarMedicos', [UsuariosController::class, 'listarMedicos'])
	->middleware('auth')
	->name('usuarios.listarMedicos');
Route::post('/usuarios/getListadoMedico', [UsuariosController::class, 'getListadoMedico'])
	->middleware('auth')
	->name('usuarios.getListadoMedico');
Route::get('/usuarios/crearMedico', [UsuariosController::class, 'crearMedico'])
	->middleware('auth')
	->name('usuarios.crearMedico');
Route::post('/usuarios/almacenarMedico', [UsuariosController::class, 'almacenarMedico'])
	->middleware('auth')
	->name('usuarios.almacenarMedico');

Route::get('/usuarios/createUsuario', [UsuariosController::class, 'createUsuario'])->name('usuarios.createUsuario');
Route::post('/usuarios/almacenarUsuario', [UsuariosController::class, 'almacenarUsuario'])->name('usuarios.almacenarUsuario');

Route::post('/usuarios/validarNombreUsuario', [UsuariosController::class, 'validarNombreUsuario'])->name('usuarios.validarNombreUsuario');
Route::post('/usuarios/validarApellidosUsuario', [UsuariosController::class, 'validarApellidosUsuario'])->name('usuarios.validarApellidosUsuario');
Route::post('/usuarios/validarCorreoUsuario', [UsuariosController::class, 'validarCorreoUsuario'])->name('usuarios.validarCorreoUsuario');
Route::post('/usuarios/validarPasswordUsuario', [UsuariosController::class, 'validarPasswordUsuario'])->name('usuarios.validarPasswordUsuario');

Route::get('/usuarios/dashboard', [UsuariosController::class, 'dashboard']) // esta ruta es para mostrar el mapa y la ubicación del usuario
	->middleware('auth')
	->name('usuarios.dashboard');

Route::get('/usuarios/configuracion', [UsuariosController::class, 'configuracion'])
	->middleware('auth')
	->name('usuarios.configuracion');

Route::get('/usuarios/estacionesAemetDisponibles', [UsuariosController::class, 'estacionesAemetDisponibles'])
	->middleware('auth')
	->name('usuarios.estacionesAemetDisponibles');

Route::post('/usuarios/guardarConfiguracion', [UsuariosController::class, 'guardarConfiguracion'])
	->middleware('auth')
	->name('usuarios.guardarConfiguracion');

Route::get('/usuarios/registrarMigrana', [MigranaController::class, 'registrarMigrana'])
	->middleware('auth')
	->name('usuarios.registrarMigrana');
Route::get('/usuarios/seguimientoMigrana', [MigranaController::class, 'seguimientoMigrana'])
	->middleware('auth')
	->name('usuarios.seguimientoMigrana');
Route::get('/usuarios/seguimientoMigrana/pdf', [MigranaController::class, 'descargarPdfSeguimiento'])
	->middleware('auth')
	->name('usuarios.seguimientoMigrana.pdf');

Route::post('/usuarios/guardarMigrana', [MigranaController::class, 'guardarMigrana'])
	->middleware('auth')
	->name('usuarios.guardarMigrana');
Route::delete('/usuarios/migranas/{id}', [MigranaController::class, 'eliminarMigrana'])
	->middleware('auth')
	->name('usuarios.eliminarMigrana');
Route::get('/usuarios/listarMigranas', [MigranaController::class, 'listarMigranas'])
	->middleware('auth')
	->name('usuarios.listarMigranas');

// Área del médico: solo ve y actúa sobre sus propios pacientes (comprobado
// dentro del controlador, no solo escondiendo el enlace en el menú).
Route::get('/usuarios/medico/pacientes', [MigranaController::class, 'misPacientes'])
	->middleware('auth')
	->name('usuarios.medico.misPacientes');
Route::get('/usuarios/medico/pacientes/{id}/migranas', [MigranaController::class, 'migranasDePaciente'])
	->middleware('auth')
	->name('usuarios.medico.migranasPaciente');
Route::post('/usuarios/medico/migranas/{id}/diagnostico', [MigranaController::class, 'guardarDiagnostico'])
	->middleware('auth')
	->name('usuarios.medico.guardarDiagnostico');

Route::get('/usuarios/login', [UsuariosController::class, 'login'])->name('usuarios.login');
Route::redirect('/login', '/usuarios/login')->name('login'); // Redirige la ruta /login a /usuarios/login si no esta autenticado
Route::post('/usuarios/validarLogin', [UsuariosController::class, 'validarLogin'])->name('usuarios.validarLogin');
Route::get('/usuarios/logout', [UsuariosController::class, 'logout'])->name('usuarios.logout');

Route::get('/google/login', [GoogleController::class, 'login'])->name('google.login');
Route::get('/google/register', [GoogleController::class, 'register'])->name('google.register');
Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');