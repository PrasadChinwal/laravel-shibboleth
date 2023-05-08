<?php

return [
    /*
     * Specify the type of authentication and authorization required.
     *
     * Supported: saml, oidc
     */

    'type' => 'saml',

    /*
     * Set config required for Shibboleth OIDC authentication and authorization.
     */

    'oidc' => [
        'client_id' => env('OIDC_CLIENT_ID'),
        'client_secret' => env('OIDC_CLIENT_SECRET'),
        'auth_url' => env('OIDC_AUTH_URL'),
        'token_url' => env('OIDC_TOKEN_URL'),
        'logout_url' => env('OIDC_LOGOUT_URL'),
        'redirect' => env('APP_URL').'/auth/callback',
        'scopes' => ['email', 'family_name', 'itrust_uin', 'given_name', 'uisedu_is_member_of'],
    ],

    'saml' => [
        'auth_url' => env("SAML_LOGIN_URL"),
        'logout_url' => env('SAML_LOGOUT_URL'),
        'redirect' => env('APP_URL').'/auth/callback',
        'entitlement' => 'isMemberOf',
        'user' => ['sn', 'givenName', 'name', 'mail', 'iTrustUIN']
    ]
];
