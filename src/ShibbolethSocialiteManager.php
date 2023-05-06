<?php

namespace prasadchinwal\shibboleth;

use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\AbstractProvider;
use prasadchinwal\shibboleth\Oidc\ShibbolethOidcProvider;
use prasadchinwal\shibboleth\Saml\ShibbolethSamlProvider;

class ShibbolethSocialiteManager extends SocialiteManager
{
    /**
     * Create a shibboleth oidc driver
     *
     * @return AbstractProvider
     */
    public function createShibOidcDriver(): AbstractProvider
    {
        $config = $this->config->get('shibboleth.oidc');

        return $this->buildProvider(ShibbolethOidcProvider::class, $config);
    }

    /**
     * Create a shibboleth saml driver
     *
     * @return ShibbolethSamlProvider
     */
    public function createShibSamlDriver(): ShibbolethSamlProvider
    {
        return new ShibbolethSamlProvider;
    }
}
