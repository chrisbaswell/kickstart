<?php

namespace Baswell\Kickstart\Http\Responses;

use Baswell\Kickstart\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Illuminate\Http\JsonResponse;

class PasswordUpdateResponse implements PasswordUpdateResponseContract
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
            ? new JsonResponse('', 200)
            : back()->with('status', 'password-updated');
    }
}
