<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/user')->group(function () {
    Route::prefix('/{id}')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::get('/projects', [UserController::class, 'getUserProjects']);
    });
});

Route::prefix('/projects')->group(function () {
    Route::post('/', [ProjectController::class, 'store']);
    Route::prefix('/{id}')->group(function () {
        Route::get('/', [ProjectController::class, 'show']);
        Route::put('/', [ProjectController::class, 'update']);
        Route::delete('/', [ProjectController::class, 'destroy']);
        Route::get('/simple-tasks-list', [ProjectController::class, 'getSimpleTasksList']);

        Route::prefix('/tasks')->group(function () {
            Route::post('/', [TaskController::class, 'store']);
            Route::prefix('/{task_id}')->group(function () {
                Route::delete('/', [TaskController::class, 'destroy']);
                Route::put('/', [TaskController::class, 'update']);
            });
        });
    });
});
