<?php

use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UsuariosController::class, 'landingPage'])->name('landingPage');

Route::get('/usuarios/obtenerListadoUsuarios', [UsuariosController::class, 'obtenerListadoUsuarios'])->name('usuarios.obtenerListadoUsuarios');
Route::post('/usuarios/getListadoUsuario', [UsuariosController::class, 'getListadoUsuario'])->name('usuarios.getListadoUsuario');

Route::post('/usuarios/eliminarUsuario', [UsuariosController::class, 'eliminarUsuario'])->name('usuarios.eliminarUsuario');


Route::get('/usuarios/createUsuario', [UsuariosController::class, 'createUsuario'])->name('usuarios.createUsuario');
Route::post('/usuarios/almacenarUsuario', [UsuariosController::class, 'almacenarUsuario'])->name('usuarios.almacenarUsuario');

Route::post('/usuarios/validarNombreUsuario',[UsuariosController::class, 'validarNombreUsuario'])->name('usuarios.validarNombreUsuario');
Route::post('/usuarios/validarApellidosUsuario',[UsuariosController::class, 'validarApellidosUsuario'])->name('usuarios.validarApellidosUsuario');
Route::post('/usuarios/validarCorreoUsuario',[UsuariosController::class, 'validarCorreoUsuario'])->name('usuarios.validarCorreoUsuario');
Route::post('/usuarios/validarPasswordUsuario',[UsuariosController::class, 'validarPasswordUsuario'])->name('usuarios.validarPasswordUsuario');

Route::get('/usuarios/dashboard', [UsuariosController::class, 'dashboard'])->name('usuarios.dashboard');

Route::get('/usuarios/login', [UsuariosController::class, 'login'])->name('usuarios.login');
Route::post('/usuarios/validarLogin', [UsuariosController::class, 'validarLogin'])->name('usuarios.validarLogin');
Route::get('/usuarios/logout', [UsuariosController::class, 'logout'])->name('usuarios.logout');