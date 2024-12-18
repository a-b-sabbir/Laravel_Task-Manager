<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware("auth:api")->group(function () {
    Route::apiResource('tasks', TaskController::class);  // use cmd-> php artisan route:list to see the api routes for this
});


Route::post('/profile', [ProfileController::class, 'store'])->middleware('auth:api');
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth:api');
Route::put('/profile/{id}', [ProfileController::class, 'update'])->middleware('auth:api');
Route::delete('/profile/{id}', [ProfileController::class, 'delete'])->middleware('auth:api');
