<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

if (config('shibboleth.type') === "oidc") {
    Route::group(['middleware' => 'web'], function () {
        Route::get('/auth/redirect', function () {
            return Socialite::driver('shib-oidc')->redirect();
        })->name('shib-oidc.redirect');
    })->name('shib-oidc');
}

if (config('shibboleth.type') === 'saml') {
//    Route::group(['middleware' => 'web'], function () {
//        Route::get('/shibboleth-logout', 'StudentAffairsUwm\Shibboleth\Controllers\ShibbolethController@destroy')
//            ->name('shib-saml.logout');
//    });
}

