<?php

use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios/obtenerListadoUsuarios',[UsuariosController::class, 'obtenerListadoUsuarios'])->name('usuarios.obtenerListadoUsuarios');
Route::post('/usuarios/getListadoUsuario',[UsuariosController::class, 'getListadoUsuario'])->name('usuarios.getListadoUsuario');

Route::post('/usuarios/eliminarUsuario',[UsuariosController::class, 'eliminarUsuario'])->name('usuarios.eliminarUsuario');
