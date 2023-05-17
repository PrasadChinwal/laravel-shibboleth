# UIS ITS Laravel Shibboleth

This package extends the Laravel's first-party package socialite to authenticate and authorize using Shibboleth.

## Usage:
- Install the package:
```composer require chinwalprasad/laravel-shibboleth```
- Optional: Add Service provider to `config/app.php` file.
```prasadchinwal/shibboleth/ShibbolethServiceProvider::class```
- Publish the config:
``` php artisan vendor:publish --tag=shib-config```
- Publish the migration:
``` php artisan vendor:publish --tag=shib-migrations```
- Set environment variables in .env file (Check the `config/shibboleth.php` file)

#### Using SAML authentication 
- Set the SAML environment variables
- Set the type property in `config/shibboleth.php` to ***saml***

#### Using OIDC authentication
- Set the OIDC environment variables
- Set the type property in `config/shibboleth.php` to ***oidc***

#### Set up authentication routes
set the authentication routes in `routes/web.php` files
```php
Route::get('/login', function () {
    return Socialite::driver(config('shibboleth.type'))->redirect();
})->name('login');

Route::get('/auth/callback', [AuthHandler::class, 'login'])
    ->name('shib.callback');

Route::get('/logout', [AuthHandler::class, 'logout'])
    ->name('logout');
```

#### Token Introspection
For token introspection using OIDC add the following middleware to the `app/Http/Kernel.php` file:

Under `web` property:
```php
\PrasadChinwal\Shibboleth\Http\Middleware\Introspect::class,
```

Under `alias` property:
```php
'introspect' => \PrasadChinwal\Shibboleth\Http\Middleware\Introspect::class,
```

Now you can use the middleware on your protected route as such:
```php
Route::middleware(['introspect'])->get('/introspect', 'Controller@index')
->name('introspect');
```

## Issues and Concerns
Please open an issue on the GitHub repository with detailed description and logs (if available).
> In case of security concerns please write an email to [UIS ITS](uisappdevdl@uis.edu). 
