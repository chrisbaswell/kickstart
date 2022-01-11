<?php

namespace Baswell\Kickstart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Baswell\Kickstart\Contracts\VerifyEmailViewResponse;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Baswell\Kickstart\Contracts\VerifyEmailViewResponse
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(config('kickstart.dashboard'))
                    : app(VerifyEmailViewResponse::class);
    }
}
