<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\RegistroController;
use App\Http\Controllers\Api\CategoriaNegocioController;
use App\Http\Controllers\Api\NegociosController;

//RUTAS PARA INICIO Y CIERRE DE SESIÓN
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//RUTAS PARA CRUD DE USUARIOS
Route::get('/usuarios', [UsersController::class, 'index']); // GET /api/v1/usuarios
Route::post('/usuarios', [UsersController::class, 'store']); // POST /api/v1/usuarios
Route::get('/usuarios/{id}', [UsersController::class, 'show']); // GET /api/v1/usuarios/{id}
Route::put('/usuarios/{id}', [UsersController::class, 'update']); // PUT /api/v1/usuarios/{id}
Route::delete('/usuarios/{id}', [UsersController::class, 'destroy']); // DELETE /api/v1/usuarios/{id}


//RUTAS PARA CRUD DE CLIENTES
Route::get('/clientes', [ClientesController::class, 'index']);
Route::post('/clientes', [ClientesController::class, 'store']);
Route::get('/clientes/{id}', [ClientesController::class, 'show']);
Route::put('/clientes/{id}', [ClientesController::class, 'update']);
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);

//RUTAS PARA CRUD DE NEGOCIOS
Route::get('/negocios', [NegociosController::class, 'index']);
Route::post('/negocios', [NegociosController::class, 'store']);
Route::get('/negocios/{id}', [NegociosController::class, 'show']);
Route::put('/negocios/{id}', [NegociosController::class, 'update']);
Route::delete('/negocios/{id}', [NegociosController::class, 'destroy']);


// Rutas para Categorías de Negocio
Route::get('/categorias', [CategoriaNegocioController::class, 'index']);
Route::post('/categorias', [CategoriaNegocioController::class, 'store']);
Route::get('/categorias/{id}', [CategoriaNegocioController::class, 'show']);
Route::put('/categorias/{id}', [CategoriaNegocioController::class, 'update']);
Route::delete('/categorias/{id}', [CategoriaNegocioController::class, 'destroy']);


// Rutas para Clientes
Route::post('/registrarclientes', [ClientesController::class, 'registrarCliente']);

// Rutas para Negocios
Route::post('/registrarnegocios', [NegociosController::class, 'registrarNegocio']);