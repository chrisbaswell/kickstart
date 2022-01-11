<?php

use Baswell\Kickstart\Features;

return [
    'home' => '/',

    'dashboard' => '/dashboard',

    'username' => 'email',
    
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updatePasswords(),
    ],
];
