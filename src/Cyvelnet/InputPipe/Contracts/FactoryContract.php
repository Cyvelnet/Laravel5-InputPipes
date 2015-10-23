<?php

namespace Cyvelnet\InputPipe\Contracts;


    /**
     * Class FactoryContract
     *
     * @package Cyvelnet\InputPipe\Contracts
     */
/**
 * Interface FactoryContract
 *
 * @package Cyvelnet\InputPipe\Contracts
 */
interface FactoryContract
{
    /**
     * Create a new pipe instance.
     *
     * @param array $data
     * @param array $pipes
     *
     * @return \Cyvelnet\InputPipe\PipesProcessor
     */
    public function make(array $data, array $pipes);

    /**
     * Extend custom pipe handlers
     *
     * @param string $pipe
     * @param \Closure|string $extension
     *
     * @return mixed
     */
    public function extend($pipe, $extension);
}
