<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\TasksController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::name('v1.')->prefix('v1')->group(function() {

    Route::controller(AuthController::class)->prefix('/authentication')->name('authentication.')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register')->middleware('auth:sanctum');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });


    Route::middleware('auth:sanctum')->group(function () {

        Route::controller(TasksController::class)->prefix('/'.Task::$CONTENT_TYPE)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::post('/create', 'store');
            Route::post('/update/{id}', 'update');
            Route::post('/delete/{id}', 'destroy');
        });

    });

});
