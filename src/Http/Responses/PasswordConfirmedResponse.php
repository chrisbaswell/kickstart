<?php

namespace Baswell\Kickstart\Http\Responses;

use Baswell\Kickstart\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Illuminate\Http\JsonResponse;

class PasswordConfirmedResponse implements PasswordConfirmedResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
                    ? new JsonResponse('', 201)
                    : redirect()->intended(config('kickstart.dashboard'));
    }
}
