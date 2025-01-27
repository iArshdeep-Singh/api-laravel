<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\TokenAuthenticator;


Route::post('register', [ApiController::class, 'signup']);
Route::post('login', [ApiController::class, 'login']);
Route::get('test', [ApiController::class, 'test']);


Route::middleware([TokenAuthenticator::class])->group(function () {
    Route::post('data', [ApiController::class, 'create']);
    Route::get('data', [ApiController::class, 'read']);
    Route::get('data/{id}', [ApiController::class, 'readById']);
    Route::put('data/{id}', [ApiController::class, 'update']); 
    Route::delete('data/{id}', [ApiController::class, 'delete']);
    Route::post('logout/{id}', [ApiController::class, 'logout']);
});
