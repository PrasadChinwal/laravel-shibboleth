<?php

namespace prasadchinwal\shibboleth;

use Laravel\Socialite\SocialiteServiceProvider;
use prasadchinwal\shibboleth\Console\ShibbolethInstall;

class ShibbolethServiceProvider extends SocialiteServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/shibboleth.php' => config_path('shibboleth.php'),
        ], 'shib-config');

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__. '/../migrations' => database_path('migrations')
        ], 'shib-migrations');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('Laravel\Socialite\Contracts\Factory', function ($app) {
            return new ShibbolethSocialiteManager($app);
        });

        // Register the shibboleth:install command
        if ($this->app->runningInConsole()) {
            $this->commands(ShibbolethInstall::class);
        }
    }

}
