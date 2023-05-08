<?php

namespace PrasadChinwal\Shibboleth\Oidc;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class ShibbolethOidcProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [
        'openid',
        'profile',
        'email',
        'phone',
        'address',
        'offline_access',
    ];

    /**
     * @var bool
     */
    protected $usesPKCE = true;

    /**
     * Set the scopes
     * @return array
     */
    public function getScopes()
    {
        if(empty(config('shibboleth.oidc.scopes'))) {
            throw new \ValueError("Scopes not set in config file");
        }
        return array_unique((array) config('shibboleth.oidc.scopes'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.oidc.auth_url'), $state);
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::HEADERS => ['Accept' => 'application/json'],
            RequestOptions::AUTH => [$this->clientId, $this->clientSecret],
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return config('services.oidc.token_url');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('services.oidc.user_url'), [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer '.$token],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        if ($this->isStateless()) {
            $fields['state'] = 'state';
        }

        return $fields;
    }

    /**
     * @return \Laravel\Socialite\Contracts\User|User|null
     */
    public function user()
    {
        return parent::user();
    }

    /**
     * @return User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'uin' => $user['uisedu_uin'],
            'netid' => $user['preferred_username'],
            'first_name' => $user['given_name'],
            'last_name' => $user['family_name'],
            'name' => $user['given_name'].' '. $user['family_name'],
            'email' => $user['email'],
            'password' => Hash::make($user['uisedu_uin'].now()),
            'groups' => $user['uisedu_is_member_of']
        ]);
    }
}
