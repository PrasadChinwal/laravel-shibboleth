# UIS ITS Laravel Shibboleth

This package extends the Laravel's first-party package socialite to authenticate and authorize using Shibboleth.

## Usage:
- Install the package:
```composer require chinwalprasad/shibboleth```
- Optional: Add Service provider to `config/app.php` file.
```prasadchinwal/shibboleth/ShibbolethServiceProvider::class```
- Publish the config:
```php artisan vendor:publish --tag=shib```

#### Using SAML authentication 
- Configure Routes

#### Using OIDC authentication
- Configure Routes


## Issues and Concerns
Please open an issue on the GitHub repository with detailed description and logs (if available).
> In case of security concerns please write an email to [UIS ITS](uisappdevdl@uis.edu). 
