<?php

namespace Baswell\Kickstart\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

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

        static::copyFiles();

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
                '@tailwindcss/forms' => '^0.4',
                '@tailwindcss/typography' => '^0.5',
                'alpinejs' => '^3.4',
                'tailwindcss' => '^3.0',
            ],
            Arr::except($packages, [
                'axios',
                'lodash',
            ]),
        );
    }

    protected static function copyFiles(): void
    {
        $filesystem = new Filesystem();
        $filesystem->delete(resource_path('js/bootstrap.js'));

        // Tailwind Configuration
        copy(__DIR__.'/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));

        // Directories...
        $filesystem->ensureDirectoryExists(app_path('View/Components'));
        $filesystem->ensureDirectoryExists(app_path('Models'));
        $filesystem->ensureDirectoryExists(resource_path('js'));
        $filesystem->ensureDirectoryExists(resource_path('css'));
        $filesystem->ensureDirectoryExists(resource_path('views/layouts'));
        $filesystem->ensureDirectoryExists(resource_path('views/vendor'));

        // Assets
        copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/User.php', app_path('Models/User.php'));

        // Factories...
        copy(__DIR__.'/../../database/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));

        // Migrations...
        copy(__DIR__.'/../../database/migrations/2014_10_12_000000_create_users_table.php', base_path('database/migrations/2014_10_12_000000_create_users_table.php'));

        // View Components...
        copy(__DIR__.'/../../stubs/app/View/Components/AppLayout.php', app_path('View/Components/AppLayout.php'));
        copy(__DIR__.'/../../stubs/app/View/Components/GuestLayout.php', app_path('View/Components/GuestLayout.php'));

        // Layouts...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/layouts', resource_path('views/layouts'));

        // Vendor view overrides...
        $filesystem->copyDirectory(__DIR__.'/../../stubs/resources/views/vendor', resource_path('views/vendor'));
    }
}
