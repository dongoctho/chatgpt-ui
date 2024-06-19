<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAiController;
use App\Http\Middleware\CheckLogin;

Route::group([
    "middleware" => [CheckLogin::class],
], function () {
    Route::group(['prefix' => 'threads'], function () {
        Route::get('/', [OpenAiController::class, 'index']);
        Route::post('/', [OpenAiController::class, 'createThread']);
        Route::get('/{id}', [OpenAiController::class, 'findThread']);
        Route::post('/{threadId}/messages', [OpenAiController::class, 'storeChat']);
        Route::get('/{threadId}/messages', [OpenAiController::class, 'getChats']);
        Route::delete('/{threadId}', [OpenAiController::class, 'deleteThread']);
        Route::patch('/{threadId}', [OpenAiController::class, 'renameThread']);
    });

    Route::get('/models', [OpenAiController::class, 'getModels']);
    Route::get('/profile', [LoginController::class, 'profile']);
    Route::post('/logout', [LoginController::class, 'logout']);
});
