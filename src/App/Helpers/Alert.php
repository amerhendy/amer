<?php

namespace Amerhendy\Amer\App\Helpers;

use Illuminate\Support\Facades\Facade;
class Alert extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alerts';
    }
}
