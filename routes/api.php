<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('estimations/sum_by_project', ['App\Http\Controllers\EstimationController', 'sumByProject']);
Route::post('clients', 'App\Http\Controllers\Api\ClientController@store');
Route::apiResource('clients', ClientController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('estimations', EstimationController::class);

// Добавим также проверку для маршрута пользователя
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
