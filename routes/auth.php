<?php

use Baswell\Kickstart\Features;
use Illuminate\Support\Facades\Route;
use Baswell\Kickstart\Http\Controllers\PasswordController;
use Baswell\Kickstart\Http\Controllers\NewPasswordController;
use Baswell\Kickstart\Http\Controllers\VerifyEmailController;
use Baswell\Kickstart\Http\Controllers\RegisteredUserController;
use Baswell\Kickstart\Http\Controllers\PasswordResetLinkController;
use Baswell\Kickstart\Http\Controllers\ConfirmablePasswordController;
use Baswell\Kickstart\Http\Controllers\AuthenticatedSessionController;
use Baswell\Kickstart\Http\Controllers\ConfirmedPasswordStatusController;
use Baswell\Kickstart\Http\Controllers\EmailVerificationPromptController;
use Baswell\Kickstart\Http\Controllers\EmailVerificationNotificationController;

Route::group(['middleware' => ['web']], function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware(['guest'])
        ->name('login');


    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['guest']);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.request');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->middleware(['guest'])
            ->name('password.reset');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest'])
            ->name('password.update');
    }

    // Registration...
    if (Features::enabled(Features::registration())) {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->middleware(['guest'])
            ->name('register');

        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest']);
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware(['auth'])
            ->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth'])
            ->name('verification.send');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-password.update');
    }

    // Password Confirmation...
    Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirm');

    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware(['auth'])
        ->name('password.confirmation');

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware(['auth']);
});
