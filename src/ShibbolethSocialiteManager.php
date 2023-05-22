<?php

namespace PrasadChinwal\Shibboleth;

use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\Two\AbstractProvider;
use PrasadChinwal\Shibboleth\Oidc\ShibbolethOidcProvider;
use PrasadChinwal\Shibboleth\Saml\ShibbolethSamlProvider;

class ShibbolethSocialiteManager extends SocialiteManager
{
    /**
     * Create a shibboleth oidc driver
     */
    public function createShibOidcDriver(): AbstractProvider
    {
        $config = $this->config->get('shibboleth.oidc');

        return $this->buildProvider(ShibbolethOidcProvider::class, $config);
    }

    /**
     * Create a shibboleth saml driver
     */
    public function createShibSamlDriver(): ShibbolethSamlProvider
    {
        return new ShibbolethSamlProvider;
    }
}
