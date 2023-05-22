<?php

namespace PrasadChinwal\Shibboleth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use Symfony\Component\HttpFoundation\Response;

class Introspect
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$scopes
     *
     * @throws \Throwable
     */
    public function handle(Request $request, Closure $next, ...$scopes): Response
    {
        throw_if(
            ! $request->hasHeader('Authorization'),
            new \Exception('Authorization Header Not Found!')
        );

        throw_if(
            empty($request->bearerToken()),
            new \Exception('Bearer Token Not Found!')
        );

        if ($this->checkCache($request->bearerToken())) {
            return $next($request);
        }

        $introspectResponse = Socialite::driver('shib-oidc')
            ->introspect($request->bearerToken());

        if (! $introspectResponse['active']) {
            throw new \Exception('Invalid token');
        }

        return $next($request);
    }

    /**
     * Return the user details from the token
     *
     * @param string $token
     * @return array $user
     */
    public function getUserByToken(string $token): array
    {
        return Socialite::driver('shib-oidc')
            ->getUserByToken($token);
    }

    /**
     * Check if the token is already authorized
     */
    protected function checkCache($token): bool
    {
        // If token not in cache return
        if (! Cache::has('introspect')) {
            return false;
        }

        if ($this->isCachedTokenValid($token)) {
            return true;
        }

        return false;
    }

    /**
     * Check if cached token is valid
     */
    protected function isCachedTokenValid(string $token): bool
    {
        $cachedToken = decrypt(Cache::get('introspect'));

        // If token valid return
        if ($cachedToken === $token) {
            return true;
        }

        return false;
    }
}
