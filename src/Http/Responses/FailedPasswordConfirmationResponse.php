<?php

namespace Baswell\Kickstart\Http\Responses;

use Baswell\Kickstart\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use Illuminate\Validation\ValidationException;

class FailedPasswordConfirmationResponse implements FailedPasswordConfirmationResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $message = __('The provided password was incorrect.');

        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'password' => [$message],
            ]);
        }

        return back()->withErrors(['password' => $message]);
    }
}
