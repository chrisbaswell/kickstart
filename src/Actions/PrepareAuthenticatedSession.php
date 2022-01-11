<?php

namespace Baswell\Kickstart\Actions;

class PrepareAuthenticatedSession
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->session()->regenerate();

        return $next($request);
    }
}
