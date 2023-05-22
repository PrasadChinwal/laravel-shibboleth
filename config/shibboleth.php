<?php

return [
    /*
     * Specify the type of authentication and authorization required.
     *
     * Supported: shib-saml, shib-oidc
     */

    'type' => 'shib-oidc',

    /*
     * Set config required for Shibboleth OIDC authentication and authorization.
     */

    'oidc' => [
        'client_id' => env('OIDC_CLIENT_ID'),
        'client_secret' => env('OIDC_CLIENT_SECRET'),
        'auth_url' => env('OIDC_AUTH_URL'),
        'token_url' => env('OIDC_TOKEN_URL'),
        'user_url' => env('OIDC_USER_URL'),
        'introspect_url' => env('OIDC_INTROSPECT_URL'),
        'logout_url' => env('OIDC_LOGOUT_URL'),
        'redirect' => env('APP_URL').'/auth/callback',
        'scopes' => ['openid', 'profile', 'email', 'phone', 'address', 'offline_access'],
    ],

    'saml' => [
        'auth_url' => env('SAML_LOGIN_URL'),
        'logout_url' => env('SAML_LOGOUT_URL'),
        'redirect' => env('APP_URL').'/auth/callback',
        'entitlement' => 'isMemberOf',
        'user' => ['sn', 'givenName', 'name', 'mail', 'iTrustUIN'],
    ],

    'authorization' => env('APP_AD_AUTHORIZE_GROUP', null),
];
