<?php

namespace Baswell\Kickstart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Baswell\Kickstart\Kickstart
 */
class Kickstart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kickstart';
    }
}
