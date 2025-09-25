<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TareaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas con middleware tenant (multiinquilino)
Route::middleware(['tenant'])->group(function () {
    
    // Login público pero debe detectar tenant
    Route::post('/login', [AuthController::class, 'login']);

    // Rutas protegidas con autenticación + tenant
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);

        // Usuarios CRUD
        Route::prefix('usuarios')->group(function () {
            Route::get('/listUsers', [UsuarioController::class, 'index']);
            Route::post('/addUser', [UsuarioController::class, 'store']);
            Route::get('/getUser/{id}', [UsuarioController::class, 'show']);
            Route::put('/updateUser/{id}', [UsuarioController::class, 'update']);
            Route::delete('/deleteUser/{id}', [UsuarioController::class, 'destroy']);
        });

        // Tareas CRUD
        Route::prefix('tareas')->group(function () {
            Route::get('/listTareas', [TareaController::class, 'index']);
            Route::post('/addTarea', [TareaController::class, 'store']);
            Route::get('/getTarea/{id}', [TareaController::class, 'show']);
            Route::put('/updateTarea/{id}', [TareaController::class, 'update']);
            Route::delete('/deleteTarea/{id}', [TareaController::class, 'destroy']);
        });
    });
});
