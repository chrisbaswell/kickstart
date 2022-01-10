<?php

namespace Baswell\Kickstart;

use Baswell\Kickstart\Commands\KickstartCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class KickstartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('kickstart')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_kickstart_table')
            ->hasCommand(KickstartCommand::class);
    }
}
