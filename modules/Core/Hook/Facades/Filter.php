<?php

namespace Modules\Core\Hook\Facades;

use Illuminate\Support\Facades\Facade;

class Filter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
    	return 'hook.filter';
    }
}