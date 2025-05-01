<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']); 
Route::post('login', [AuthController::class, 'login']); 

Route::middleware('auth:api')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    
    Route::get('projects/{project}/tasks', [TaskController::class, 'index']); 
    Route::post('projects/{project}/tasks', [TaskController::class, 'store']); 
    Route::post('/tasks/{taskId}/add-users', [TaskController::class, 'addUsersToTask']);
    
    Route::get('tasks/{task}', [TaskController::class, 'show']); 
    Route::put('tasks/{task}', [TaskController::class, 'update']); 
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']); 
     // Rutas para usuarios (solo para admin o usuario autenticado)
     Route::get('users', [UserController::class, 'index']);
     Route::get('users/{id}', [UserController::class, 'show']);
     Route::put('users/{id}', [UserController::class, 'update']);
     Route::delete('users/{id}', [UserController::class, 'destroy']);
});
