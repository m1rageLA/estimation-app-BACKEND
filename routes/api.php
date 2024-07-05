<?php

use Illuminate\Http\Request;
use App\Mail\TestMail; // Создайте класс для тестового письма
use Illuminate\Support\Facades\Mail;
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

    Route::put('update', ['App\Http\Controllers\AuthController', 'update']);

    Route::get('/login/{id}', ['App\Http\Controllers\AuthController', 'getUser'])->name('getUser');
});

// Маршрут для страницы ввода email для сброса пароля
Route::get('/forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');


Route::get('/send-test-email', function () {
    Mail::to('miragela.code@gmail.com')->send(new TestMail());
    return 'Тестовое письмо отправлено!';
});
Route::get('password/reset', ['App\Http\Controllers\Auth\ForgotPasswordController', 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', ['App\Http\Controllers\Auth\ForgotPasswordController', 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', ['App\Http\Controllers\Auth\ResetPasswordController', 'showResetForm'])->name('password.reset');
Route::post('password/reset', ['App\Http\Controllers\Auth\ResetPasswordController', 'reset'])->name('password.update');



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
