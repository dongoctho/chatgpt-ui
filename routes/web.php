<?php

use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckCallBack;
use App\Http\Middleware\CheckLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.index');
})->middleware(CheckLogin::class)->name('home');
Route::get('/login', function () {
    return view('layouts.index');
})->name('login');
Route::get('/auth/google',[LoginController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->middleware(CheckCallBack::class);
