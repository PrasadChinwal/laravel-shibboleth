<?php

use Illuminate\Support\Facades\Route;
use PrasadChinwal\Shibboleth\Actions\AuthHandler;

Route::group([
    'as' => 'shib.',
], function () {
    Route::name('login')->get('login', [AuthHandler::class, 'login']);

    Route::name('callback')->get('/auth/callback', [AuthHandler::class, 'callback']);

    Route::name('logout')->get('/logout', [AuthHandler::class, 'logout']);
});
