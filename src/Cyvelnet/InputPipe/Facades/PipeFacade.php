<?php

namespace Cyvelnet\InputPipe\Facades;

use Illuminate\Support\Facades\Facade;

class PipeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pipes';
    }
}
