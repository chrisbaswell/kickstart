<?php

namespace Baswell\Kickstart\Http\Controllers;

use Baswell\Kickstart\Http\Requests\VerifyEmailRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Baswell\Kickstart\Http\Requests\VerifyEmailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(VerifyEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : redirect()->intended(config('kickstart.dashboard').'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $request->wantsJson()
            ? new JsonResponse('', 202)
            : redirect()->intended(config('kickstart.dashboard').'?verified=1');
    }
}
