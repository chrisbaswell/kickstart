<?php

namespace Baswell\Kickstart;

use Baswell\Kickstart\Contracts\ConfirmPasswordViewResponse;
use Baswell\Kickstart\Contracts\CreatesNewUsers;
use Baswell\Kickstart\Contracts\LoginViewResponse;
use Baswell\Kickstart\Contracts\RegisterViewResponse;
use Baswell\Kickstart\Contracts\RequestPasswordResetLinkViewResponse;
use Baswell\Kickstart\Contracts\ResetPasswordViewResponse;
use Baswell\Kickstart\Contracts\ResetsUserPasswords;
use Baswell\Kickstart\Contracts\UpdatesUserPasswords;
use Baswell\Kickstart\Contracts\VerifyEmailViewResponse;
use Baswell\Kickstart\Http\Responses\SimpleViewResponse;

class kickstart
{
    /**
     * The callback that is responsible for building the authentication pipeline array, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateThroughCallback;

    /**
     * The callback that is responsible for validating authentication credentials, if applicable.
     *
     * @var callable|null
     */
    public static $authenticateUsingCallback;

    /**
     * The callback that is responsible for confirming user passwords.
     *
     * @var callable|null
     */
    public static $confirmPasswordsUsingCallback;

    /**
     * Get the username used for authentication.
     *
     * @return string
     */
    public static function username()
    {
        return config('kickstart.username', 'email');
    }

    /**
     * Register the views for kickstart using conventional names under the given prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public static function viewPrefix(string $prefix)
    {
        static::loginView($prefix.'login');
        static::registerView($prefix.'register');
        static::requestPasswordResetLinkView($prefix.'forgot-password');
        static::resetPasswordView($prefix.'reset-password');
        static::verifyEmailView($prefix.'verify-email');
        static::confirmPasswordView($prefix.'confirm-password');
    }

    /**
     * Specify which view should be used as the login view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function loginView($view)
    {
        app()->singleton(LoginViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the new password view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function resetPasswordView($view)
    {
        app()->singleton(ResetPasswordViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the registration view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function registerView($view)
    {
        app()->singleton(RegisterViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the email verification prompt.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function verifyEmailView($view)
    {
        app()->singleton(VerifyEmailViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the password confirmation prompt.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function confirmPasswordView($view)
    {
        app()->singleton(ConfirmPasswordViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Specify which view should be used as the request password reset link view.
     *
     * @param  callable|string  $view
     * @return void
     */
    public static function requestPasswordResetLinkView($view)
    {
        app()->singleton(RequestPasswordResetLinkViewResponse::class, function () use ($view) {
            return new SimpleViewResponse($view);
        });
    }

    /**
     * Register a callback that is responsible for building the authentication pipeline array.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function authenticateThrough(callable $callback)
    {
        static::$authenticateThroughCallback = $callback;
    }

    /**
     * Register a callback that is responsible for validating incoming authentication credentials.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function authenticateUsing(callable $callback)
    {
        static::$authenticateUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for confirming existing user passwords as valid.
     *
     * @param  callable  $callback
     * @return void
     */
    public static function confirmPasswordsUsing(callable $callback)
    {
        static::$confirmPasswordsUsingCallback = $callback;
    }

    /**
     * Register a class / callback that should be used to create new users.
     *
     * @param  string  $callback
     * @return void
     */
    public static function createUsersUsing(string $callback)
    {
        app()->singleton(CreatesNewUsers::class, $callback);
    }

    /**
     * Register a class / callback that should be used to update user passwords.
     *
     * @param  string  $callback
     * @return void
     */
    public static function updateUserPasswordsUsing(string $callback)
    {
        app()->singleton(UpdatesUserPasswords::class, $callback);
    }

    /**
     * Register a class / callback that should be used to reset user passwords.
     *
     * @param  string  $callback
     * @return void
     */
    public static function resetUserPasswordsUsing(string $callback)
    {
        app()->singleton(ResetsUserPasswords::class, $callback);
    }
}
