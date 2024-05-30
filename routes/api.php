<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('estimations/sum_by_project', ['App\Http\Controllers\EstimationController', 'sumByProject']);
Route::post('clients', 'App\Http\Controllers\Api\ClientController@store');
Route::post('clients', 'App\Http\Controllers\Api\ClientController@index');
Route::apiResource('clients', ClientController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('estimations', EstimationController::class);
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
