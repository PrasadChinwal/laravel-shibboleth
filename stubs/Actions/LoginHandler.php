<?php


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginHandler
{
    /**
     *
     */
    public function __invoke()
    {
        $user = Socialite::driver('shib-oidc')->user();

        $user = User::updateOrCreate([
            'github_id' => $user->id,
        ], [
            'name' => $user->name,
            'email' => $user->email,
            'github_token' => $user->token,
            'github_refresh_token' => $user->refreshToken,
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }
}
