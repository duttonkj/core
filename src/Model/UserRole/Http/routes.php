<?php

use Ohio\Core\Model\UserRole;

/**
 * API
 */
Route::group([
    'prefix' => 'api/v1',
    'middleware' => ['api']
],
    function () {
        Route::get('/users-roles/{id}', UserRole\Http\Controllers\ApiController::class . '@show');
        Route::put('/users-roles/{id}', UserRole\Http\Controllers\ApiController::class . '@update');
        Route::delete('/users-roles/{id}', UserRole\Http\Controllers\ApiController::class . '@destroy');
        Route::get('/users-roles', UserRole\Http\Controllers\ApiController::class . '@index');
        Route::post('/users-roles', UserRole\Http\Controllers\ApiController::class . '@store');
    }
);

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web']
],
    function () {
            Route::get('/users-roles/{id}', UserRole\Http\Controllers\AdminController::class . '@show');
            Route::get('/users-roles', UserRole\Http\Controllers\AdminController::class . '@index');
            Route::get('/users-roles-vue', UserRole\Http\Controllers\AdminController::class . '@indexVue');
    }
);