<?php

namespace Baswell\Kickstart\Http\Controllers;

use Illuminate\Http\Request;
use Baswell\Kickstart\Fortify;
use Baswell\Kickstart\Features;
use Baswell\Kickstart\Kickstart;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Baswell\Kickstart\Contracts\LoginResponse;
use Baswell\Kickstart\Contracts\LogoutResponse;
use Baswell\Kickstart\Http\Requests\LoginRequest;
use Baswell\Kickstart\Contracts\LoginViewResponse;
use Baswell\Kickstart\Actions\AttemptToAuthenticate;
use Baswell\Kickstart\Actions\PrepareAuthenticatedSession;

class AuthenticatedSessionController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the login view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Baswell\Kickstart\Contracts\LoginViewResponse
     */
    public function create(Request $request): LoginViewResponse
    {
        return app(LoginViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param  \Baswell\Kickstart\Http\Requests\LoginRequest  $request
     * @return mixed
     */
    public function store(LoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    /**
     * Get the authentication pipeline instance.
     *
     * @param  \Baswell\Kickstart\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Pipeline\Pipeline
     */
    protected function loginPipeline(LoginRequest $request)
    {
        if (Kickstart::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Kickstart::$authenticateThroughCallback, $request)
            ));
        }

        if (is_array(config('kickstart.pipelines.login'))) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                config('kickstart.pipelines.login')
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Baswell\Kickstart\Contracts\LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
