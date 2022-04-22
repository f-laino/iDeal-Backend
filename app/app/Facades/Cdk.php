<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Cdk extends Facade
{
    /**
     * Create the Facade
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'cdk'; }
}
