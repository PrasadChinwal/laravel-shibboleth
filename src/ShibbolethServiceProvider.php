<?php

namespace PrasadChinwal\Shibboleth;

use Laravel\Socialite\SocialiteServiceProvider;
use PrasadChinwal\Shibboleth\Console\ShibbolethInstall;

class ShibbolethServiceProvider extends SocialiteServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/shibboleth.php' => config_path('shibboleth.php'),
        ], 'shib-config');

        $this->loadRoutes();

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
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

    /**
     * Register routes required for authentication and introspection
     */
    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(realpath(__DIR__.'/routes/routes.php'));
    }
}
