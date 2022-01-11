<?php

namespace Baswell\Kickstart\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class KickstartCommand extends Command
{
    public $signature = 'kickstart:install';

    public $description = 'Kickstart the app';

    public function handle(): int
    {
        $this->callSilent('vendor:publish', ['--tag' => 'kickstart-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'tables-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'forms-config', '--force' => true]);

        static::updateNpmPackages();

        $this->configureFiles();

        $this->info('Kickstarted app successfully.');

        $this->comment('Please run `npm install && npm run dev` to compile your new assets.');

        return static::SUCCESS;
    }

    protected static function updateNpmPackages(bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = static::updateNpmPackageArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : []
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    protected static function updateNpmPackageArray(array $packages): array
    {
        return array_merge(
            [
                '@tailwindcss/typography' => '^0.5',
                '@tailwindcss/line-clamp' => '^0.2',
                '@tailwindcss/aspect-ratio' => '^0.3',
                'alpinejs' => '^3.7',
                'tailwindcss' => '^3.0',
                '@alpinejs/trap' => '^3.7',
            ],
            Arr::except($packages, [
                'axios',
                'lodash',
            ]),
        );
    }

    protected function configureFiles(): void
    {
        $filesystem = new Filesystem();
        $filesystem->delete(resource_path('js/bootstrap.js'));

        // Dashboard...
        $this->replaceInFile('/home', config('kickstart.dashboard'), app_path('Providers/RouteServiceProvider.php'));

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            $this->replaceInFile('/home', config('kickstart.dashboard'), resource_path('views/welcome.blade.php'));
            $this->replaceInFile('Home', 'Go to App', resource_path('views/welcome.blade.php'));
        }

        if (! Str::contains(file_get_contents(base_path('routes/web.php')), config('kickstart.dashboard'))) {
            (new Filesystem())->append(base_path('routes/web.php'), $this->routeDefinition());
        }

        // Directories...
        $filesystem->ensureDirectoryExists(app_path('Actions/Auth'));
        $filesystem->ensureDirectoryExists(app_path('View/Components'));
        $filesystem->ensureDirectoryExists(app_path('Models'));
        $filesystem->ensureDirectoryExists(resource_path('js'));
        $filesystem->ensureDirectoryExists(resource_path('css'));
        $filesystem->ensureDirectoryExists(resource_path('views/layouts'));
        $filesystem->ensureDirectoryExists(resource_path('views/vendor'));

        // Factories...
        copy(__DIR__.'/../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));

        // Migrations...
        copy(__DIR__.'/../../database/migrations/2014_10_12_000000_create_users_table.php', base_path('database/migrations/2014_10_12_000000_create_users_table.php'));

        // Tailwind Configuration...
        copy(__DIR__.'/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));

        // Service Providers...
        copy(__DIR__.'/../../stubs/app/Providers/KickstartServiceProvider.php', app_path('Providers/KickstartServiceProvider.php'));
        $this->installServiceProviderAfter('RouteServiceProvider', 'KickstartServiceProvider');

        // Assets...
        copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Auth/CreateNewUser.php', app_path('Actions/Auth/CreateNewUser.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Auth/ResetUserPassword.php', app_path('Actions/Auth/ResetUserPassword.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Auth/UpdateUserPassword.php', app_path('Actions/Auth/UpdateUserPassword.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Auth/PasswordValidationRules.php', app_path('Actions/Auth/PasswordValidationRules.php'));

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // View Components...
        copy(__DIR__.'/../../stubs/app/View/Components/AppLayout.php', app_path('View/Components/AppLayout.php'));
        copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));

        // Single Blade Views...
        copy(__DIR__.'/../../stubs/resources/views/dashboard.blade.php', resource_path('views/dashboard.blade.php'));

        // Layouts...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));

        // Vendor view overrides...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/vendor-overrides', resource_path('views/vendor'));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Get the route definition(s) that should be installed
     *
     * @return string
     */
    protected function routeDefinition()
    {
        return <<<'EOF'
Route::middleware(['auth'])->get(config('kickstart.dashboard'), function () {
    return view('dashboard');
})->name('dashboard');
EOF;
    }

    /**
     * Install the service provider in the application configuration file.
     *
     * @param  string  $after
     * @param  string  $name
     * @return void
     */
    protected function installServiceProviderAfter($after, $name)
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\'.$name.'::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\'.$after.'::class,',
                'App\\Providers\\'.$after.'::class,'.PHP_EOL.'        App\\Providers\\'.$name.'::class,',
                $appConfig
            ));
        }
    }
}
