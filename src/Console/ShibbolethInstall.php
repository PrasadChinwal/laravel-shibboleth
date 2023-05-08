<?php

namespace PrasadChinwal\Shibboleth\Console;

use Illuminate\Console\Command;

class ShibbolethInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shibboleth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Shibboleth OIDC/SAML components.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // publish config file
        $this->callSilent('vendor:publish', ['--tag' => 'shib-config', '--force' => true]);
        $this->info('Successfully published shibboleth configuration to: '.config_path('shibboleth.php'));
        $this->newLine();

        // publish migration file
        $this->callSilent('vendor:publish', ['--tag' => 'shib-migrations', '--force' => true]);
        $this->info("Successfully published shibboleth migrations to: ". database_path());
        $this->newLine();

        $this->info('Please run your migrations using:');
        $this->warn('php artisan migrate');
    }
}
