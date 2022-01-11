<?php

namespace Baswell\Kickstart\Http\Controllers;

use Baswell\Kickstart\Contracts\PasswordUpdateResponse;
use Baswell\Kickstart\Contracts\UpdatesUserPasswords;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Baswell\Kickstart\Contracts\UpdatesUserPasswords  $updater
     * @return \Baswell\Kickstart\Contracts\PasswordUpdateResponse
     */
    public function update(Request $request, UpdatesUserPasswords $updater)
    {
        $updater->update($request->user(), $request->all());

        return app(PasswordUpdateResponse::class);
    }
}
