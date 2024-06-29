<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController; // Importa el controlador

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Estas rutas son cargadas por RouteServiceProvider y todas ellas
| serán asignadas al grupo de middleware "web". ¡Haz algo grandioso!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para UsersController
Route::get('/users', [UsersController::class, 'index']);
Route::post('/users', [UsersController::class, 'store']);
Route::get('/users/{id}', [UsersController::class, 'show']);
Route::put('/users/{id}', [UsersController::class, 'update']);
Route::delete('/users/{id}', [UsersController::class, 'destroy']);

