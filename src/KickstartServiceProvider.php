<?php

namespace Baswell\Kickstart;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\View\Compilers\BladeCompiler;
use Baswell\Kickstart\Commands\KickstartCommand;
use Baswell\Kickstart\Http\Responses\LoginResponse;
use Baswell\Kickstart\Http\Responses\LogoutResponse;
use Baswell\Kickstart\Http\Responses\RegisterResponse;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Baswell\Kickstart\Http\Responses\PasswordResetResponse;
use Baswell\Kickstart\Http\Responses\PasswordUpdateResponse;
use Baswell\Kickstart\Http\Responses\PasswordConfirmedResponse;
use Baswell\Kickstart\Http\Responses\FailedPasswordResetResponse;
use Baswell\Kickstart\Contracts\LoginResponse as LoginResponseContract;
use Baswell\Kickstart\Http\Responses\FailedPasswordConfirmationResponse;
use Baswell\Kickstart\Contracts\LogoutResponse as LogoutResponseContract;
use Baswell\Kickstart\Http\Responses\FailedPasswordResetLinkRequestResponse;
use Baswell\Kickstart\Contracts\RegisterResponse as RegisterResponseContract;
use Baswell\Kickstart\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;
use Baswell\Kickstart\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Baswell\Kickstart\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Baswell\Kickstart\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Baswell\Kickstart\Contracts\FailedPasswordResetResponse as FailedPasswordResetResponseContract;
use Baswell\Kickstart\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use Baswell\Kickstart\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;
use Baswell\Kickstart\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;

class KickstartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('kickstart')
            ->hasConfigFile('kickstart')
            ->hasViews('kickstart')
            ->hasCommand(KickstartCommand::class);
    }

    public function packageRegistered()
    {
        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard('web');
        });

        $this->app->singleton(FailedPasswordConfirmationResponseContract::class, FailedPasswordConfirmationResponse::class);
        $this->app->singleton(FailedPasswordResetLinkRequestResponseContract::class, FailedPasswordResetLinkRequestResponse::class);
        $this->app->singleton(FailedPasswordResetResponseContract::class, FailedPasswordResetResponse::class);
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(PasswordConfirmedResponseContract::class, PasswordConfirmedResponse::class);
        $this->app->singleton(PasswordResetResponseContract::class, PasswordResetResponse::class);
        $this->app->singleton(PasswordUpdateResponseContract::class, PasswordUpdateResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(SuccessfulPasswordResetLinkRequestResponseContract::class, SuccessfulPasswordResetLinkRequestResponse::class);
    }

    public function packageBooted()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->bootComponent('button');
        });

        Kickstart::viewPrefix('auth.');

        Route::group([
            'prefix' => 'auth',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
        });
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function bootComponent(string $component)
    {
        Blade::component('kickstart::components.'.$component, 'ui-'.$component);
    }
}
