<?php

use Ohio\Core\User;

/**
 * Front
 */
Route::group(['middleware' => ['web']], function () {
    Route::get('login', \Ohio\Core\User\Http\Controllers\LoginController::class . '@showLoginForm');
    Route::post('login', \Ohio\Core\User\Http\Controllers\LoginController::class . '@login');
    Route::get('logout', \Ohio\Core\User\Http\Controllers\LoginController::class . '@logout');
});

/**
 * API
 */
Route::group([
    'prefix' => 'api/v1',
    'middleware' => ['api']
],
    function () {
        Route::get('/users/{id}', User\Http\Controllers\ApiController::class . '@show');
        Route::put('/users/{id}', User\Http\Controllers\ApiController::class . '@update');
        Route::delete('/users/{id}', User\Http\Controllers\ApiController::class . '@destroy');
        Route::get('/users', User\Http\Controllers\ApiController::class . '@index');
        Route::post('/users', User\Http\Controllers\ApiController::class . '@store');
    }
);