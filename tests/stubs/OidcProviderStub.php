<?php

namespace PrasadChinwal\Shibboleth\Test\stubs;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use Mockery as m;
use stdClass;

class OidcProviderStub extends AbstractProvider
{
    /**
     * @var \GuzzleHttp\Client|\Mockery\MockInterface
     */
    public $http;

    protected $usesPKCE = true;

    protected $scopeSeparator = ' ';

    protected $scopes = [
        'openid',
        'profile',
        'email',
        'phone',
        'address',
        'offline_access',
    ];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('http://auth.url', $state);
    }

    protected function getTokenUrl()
    {
        return 'http://token.url';
    }

    protected function getUserByToken($token)
    {
        return [
            'uisedu_uin' => '123456789',
            'preferred_username' => 'abc',
            'given_name' => 'first',
            'family_name' => 'last',
            'email' => 'abc@xxx.org',
        ];
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'uin' => $user['uisedu_uin'],
            'netid' => $user['preferred_username'],
            'first_name' => $user['given_name'],
            'last_name' => $user['family_name'],
            'full_name' => $user['given_name'].' '. $user['family_name'],
            'email' => $user['email'],
        ]);
    }

    /**
     * Get a fresh instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client|\Mockery\MockInterface
     */
    protected function getHttpClient()
    {
        if ($this->http) {
            return $this->http;
        }

        return $this->http = m::mock(stdClass::class);
    }
}
