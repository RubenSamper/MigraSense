<?php

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UsuariosController::class, 'landingPage'])->name('landingPage');

Route::get('/usuarios/obtenerListadoUsuarios', [UsuariosController::class, 'obtenerListadoUsuarios'])->name('usuarios.obtenerListadoUsuarios');
Route::post('/usuarios/getListadoUsuario', [UsuariosController::class, 'getListadoUsuario'])->name('usuarios.getListadoUsuario');

Route::post('/usuarios/eliminarUsuario', [UsuariosController::class, 'eliminarUsuario'])->name('usuarios.eliminarUsuario');

Route::get('/usuarios/editarUsuario/{id}', [UsuariosController::class, 'editarUsuario'])->name('usuarios.editarUsuario');
Route::post('/usuarios/actualizarUsuario/{id}', [UsuariosController::class, 'actualizarUsuario'])->name('usuarios.actualizarUsuario');

Route::get('/usuarios/createUsuario', [UsuariosController::class, 'createUsuario'])->name('usuarios.createUsuario');
Route::post('/usuarios/almacenarUsuario', [UsuariosController::class, 'almacenarUsuario'])->name('usuarios.almacenarUsuario');

Route::post('/usuarios/validarNombreUsuario',[UsuariosController::class, 'validarNombreUsuario'])->name('usuarios.validarNombreUsuario');
Route::post('/usuarios/validarApellidosUsuario',[UsuariosController::class, 'validarApellidosUsuario'])->name('usuarios.validarApellidosUsuario');
Route::post('/usuarios/validarCorreoUsuario',[UsuariosController::class, 'validarCorreoUsuario'])->name('usuarios.validarCorreoUsuario');
Route::post('/usuarios/validarPasswordUsuario',[UsuariosController::class, 'validarPasswordUsuario'])->name('usuarios.validarPasswordUsuario');

Route::get('/usuarios/dashboard', [UsuariosController::class, 'dashboard'])->name('usuarios.dashboard');
Route::post('/usuarios/guardarZona', [UsuariosController::class, 'guardarZona'])->name('usuarios.guardarZona');

Route::get('/usuarios/login', [UsuariosController::class, 'login'])->name('usuarios.login');
Route::post('/usuarios/validarLogin', [UsuariosController::class, 'validarLogin'])->name('usuarios.validarLogin');
Route::get('/usuarios/logout', [UsuariosController::class, 'logout'])->name('usuarios.logout');

Route::get('/google/login',[GoogleController::class, 'login'])->name('google.login');
Route::get('/google/callback',[GoogleController::class, 'callback'])->name('google.callback');
