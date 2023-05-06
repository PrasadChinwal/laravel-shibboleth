<?php

namespace prasadchinwal\shibboleth\Saml;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Two\User;

abstract class AbstractSamlProvider
{
    /**
     * @var User|Null
     */
    protected ?User $user;

    /**
     * Attributes set in $_SERVER after successful authentication
     * @var array
     */
    protected array $attributes;

    /**
     * Builds the url to redirect for authentication
     *
     * @return string
     */
    public abstract function getAuthUrl(): string;

    /**
     * Redirect the user to IDP to authenticate
     *
     * @return RedirectResponse
     */
    public abstract function redirect(): RedirectResponse;

    /**
     * Return a Socialite User object for the authenticated user
     *
     * @return User
     */
    public abstract function user(): User;

    /**
     * Map the array of user attributes to Socialite User object
     *
     * @param array $user
     * @return User
     */
    public abstract function mapUserToObject(array $user): User;
}
