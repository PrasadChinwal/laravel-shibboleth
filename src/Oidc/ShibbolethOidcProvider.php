<?php

namespace PrasadChinwal\Shibboleth\Oidc;

use GuzzleHttp\RequestOptions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
     *
     * @return array
     */
    public function getScopes()
    {
        if (empty(config('shibboleth.oidc.scopes'))) {
            throw new \ValueError('Scopes not set in config file');
        }

        return array_unique((array) config('shibboleth.oidc.scopes'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('shibboleth.oidc.auth_url'), $state);
    }

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
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
        return config('shibboleth.oidc.token_url');
    }

    /**
     * Get the url to retrieve user by token
     *
     * @return string|null
     */
    protected function getUserUrl()
    {
        return config('shibboleth.oidc.user_url');
    }

    /**
     * Get the url to introspect user token
     *
     * @return string|null
     */
    protected function getIntrospectUrl()
    {
        return config('shibboleth.oidc.introspect_url');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getUserUrl(), [
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
     * @return User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'uin' => $user['uisedu_uin'],
            'netid' => $user['preferred_username'],
            'first_name' => $user['given_name'],
            'last_name' => $user['family_name'],
            'name' => $user['given_name'].' '.$user['family_name'],
            'email' => $user['email'],
            'password' => Hash::make($user['uisedu_uin'].now()),
            'groups' => $user['uisedu_is_member_of'],
        ]);
    }

    /**
     * Introspect the user token
     *
     * @return array|mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function introspect($token): mixed
    {
        $response = $this->getHttpClient()->post(
            $this->getIntrospectUrl(), [
                RequestOptions::FORM_PARAMS => [
                    'token' => $token,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Logout currently authenticated User
     *
     * @throws \Throwable
     */
    public function logout(): RedirectResponse
    {
        $user = Auth::user();
        throw_if(! $user, AuthenticationException::class);
        $logout_url = config('shibboleth.oidc.logout_url');
        $response = $this->getHttpClient()->get($logout_url, [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer '.$user->token],
        ]);

        if ($response->getStatusCode() === 200) {
            Auth::logout();
            Session::flush();

            return new RedirectResponse(config('shibboleth.oidc.logout_url'));
        }

        throw new \Exception('Could not Logout User!');
    }
}
