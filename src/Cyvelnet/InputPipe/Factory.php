<?php

namespace Cyvelnet\InputPipe;


use Cyvelnet\InputPipe\Contracts\FactoryContract;

/**
 * Class Factory
 *
 * @package Cyvelnet\InputPipe
 */
class Factory implements FactoryContract
{
    protected $extensions = [];

    protected $extra = null;

    /**
     * Create a new pipe instance.
     *
     * @param array $data
     * @param array $pipes
     *
     * @return \Cyvelnet\InputPipe\Pipe
     */
    public function make(array $data, array $pipes)
    {
        $processor = $this->resolve($data, $pipes);

        $processor->addExtensions($this->extensions);

        return $processor;
    }

    /**
     * Extend custom pipe handlers
     *
     * @param string $pipe
     * @param \Closure|string $extension
     *
     * @return mixed
     */
    public function extend($pipe, $extension)
    {
        $this->extensions[$pipe] = $extension;

    }

    /**
     * @return \Cyvelnet\InputPipe\Pipe
     */
    public function resolve($data, $pipes)
    {
        if (is_null($this->extra)) {

            return new Pipe($data, $pipes);
        }

        return call_user_func($this->extra, $data, $pipes);
    }

    /**
     * add extra/custom pipe
     *
     * @param $extra
     */
    public function extra(callable $extra)
    {
        $this->extra = $extra;
    }

}
