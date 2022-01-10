<?php

namespace Baswell\Kickstart;

use Baswell\Kickstart\Commands\KickstartCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
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
            ->hasCommand(KickstartCommand::class);

        $this->configureComponents();
    }

    /**
     * Configure the Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('button');
        });
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('kickstart::components.'.$component, 'ui-'.$component);
    }
}
