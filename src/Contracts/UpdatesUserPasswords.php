<?php

namespace Baswell\Kickstart\Contracts;

interface UpdatesUserPasswords
{
    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input);
}
