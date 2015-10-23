<?php

namespace Cyvelnet\InputPipe\Facades;

use Illuminate\Support\Facades\Facade;

class PipeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'pipes';
    }

}
