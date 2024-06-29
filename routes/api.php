<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;

// Rutas protegidas con autenticación Sanctum

// Rutas públicas sin autenticación
Route::get('/', function () {
    return response()->json(['message' => 'Esta es una prueba']);
});

Route::get('/users', [UsersController::class, 'index']);
Route::post('/users', [UsersController::class, 'store']);
Route::get('/users/{id}', [UsersController::class, 'show']);
Route::put('/users/{id}', [UsersController::class, 'update']);
Route::delete('/users/{id}', [UsersController::class, 'destroy']);

// Ruta de fallback en caso de no coincidir ninguna ruta
Route::fallback(function () {
    return response()->json(['error' => 'Ruta no encontrada'], 404);
});
