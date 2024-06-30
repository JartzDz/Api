<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\RegistroController;
use App\Http\Controllers\Api\CategoriaNegocioController;
use App\Http\Controllers\Api\NegociosController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductosController;


//RUTAS PARA INICIO Y CIERRE DE SESIÓN
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

//RUTAS PARA CRUD DE USUARIOS
Route::get('/usuarios', [UsersController::class, 'index']); // GET /api/v1/usuarios
Route::post('/usuarios', [UsersController::class, 'store']); // POST /api/v1/usuarios
Route::get('/usuarios_id/{id}', [UsersController::class, 'show']); // GET /api/v1/usuarios/{id}
Route::get('/usuarios_email/{email}', [UsersController::class, 'show_email']); // GET /api/v1/usuarios/{id}
Route::put('/usuarios/{id}', [UsersController::class, 'update']); // PUT /api/v1/usuarios/{id}
Route::delete('/usuarios/{id}', [UsersController::class, 'destroy']); // DELETE /api/v1/usuarios/{id}


//RUTAS PARA CRUD DE CLIENTES
Route::get('/clientes', [ClientesController::class, 'index']);
Route::post('/clientes', [ClientesController::class, 'store']);
Route::get('/clientes/{id}', [ClientesController::class, 'show']);
Route::post('/clientes/{id}', [ClientesController::class, 'update']);
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy']);

//RUTAS PARA CRUD DE NEGOCIOS
Route::get('/negocios', [NegociosController::class, 'index']);
Route::post('/negocios', [NegociosController::class, 'store']);
Route::get('/negocios/{id}', [NegociosController::class, 'show']);
Route::post('/negocios/{id}', [NegociosController::class, 'update']);
Route::delete('/negocios/{id}', [NegociosController::class, 'destroy']);


// Rutas para Categorías de Negocio
Route::get('/categorias-negocio', [CategoriaNegocioController::class, 'index']);
Route::post('/categorias-negocio', [CategoriaNegocioController::class, 'store']);
Route::get('/categorias-negocio/{id}', [CategoriaNegocioController::class, 'show']);
Route::put('/categorias-negocio/{id}', [CategoriaNegocioController::class, 'update']);
Route::delete('/categorias-negocio/{id}', [CategoriaNegocioController::class, 'destroy']);


// Rutas para Categorías de Cada Negocio
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

// Rutas para Categorías de Cada Negocio
Route::get('/productos', [ProductosController::class, 'index']);
Route::post('/productos', [ProductosController::class, 'store']);
Route::get('/productos/{id}', [ProductosController::class, 'show']);
Route::put('/productos/{id}', [ProductosController::class, 'update']);
Route::delete('/productos/{id}', [ProductosController::class, 'destroy']);


// Rutas para Clientes
Route::post('/registrar-cliente', [RegistroController::class, 'registrarCliente']);

// Rutas para Negocios
Route::post('/registrar-negocio', [RegistroController::class, 'registrarNegocio']);