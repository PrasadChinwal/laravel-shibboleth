<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use PrasadChinwal\Shibboleth\Actions\AuthHandler;

Route::name('login')->get('/login', function () {
    return Socialite::driver(config('shibboleth.type'))->redirect();
});

Route::name('shib.callback')->get('/auth/callback', [AuthHandler::class, 'login']);

Route::name('logout')->get('/logout', [AuthHandler::class, 'logout']);
