<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::delete('/clients/delete/{id}', [ClientController::class, 'destroy']);
    Route::apiResource('clients', ClientController::class);

    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects', [ProjectController::class, 'index']);
    Route::delete('/projects/delete/{id}', [ProjectController::class, 'destroy']);
    Route::apiResource('projects', ProjectController::class);

    Route::post('estimates', [EstimationController::class, 'store']);
    Route::get('estimates', [EstimationController::class, 'index']);
    Route::delete('/estimates/delete/{id}', [EstimationController::class, 'destroy']);
    Route::put('/estimates/{estimation}', ['App\Http\Controllers\EstimationController', 'update']);
    Route::apiResource('estimates', EstimationController::class);

    Route::get('/login/{id}', ['App\Http\Controllers\AuthController', 'getUser'])->name('getUser');
});


Route::post('/register', ['App\Http\Controllers\AuthController', 'register'])->name('register');
Route::post('/login', ['App\Http\Controllers\AuthController', 'login'])->name('login');


// Маршрут для входа в систему (логин)

// Маршрут для выхода из системы (логаут)
Route::post('/logout', ['App\Http\Controllers\AuthController', 'logout'])->name('logout');



Route::get('estimations/sum_by_project', ['App\Http\Controllers\EstimationController', 'sumByProject']);

Route::post('images', ['App\Http\Controllers\ImageController', 'store']);

Route::get('/storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');


// Добавим также проверку для маршрута пользователя
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
