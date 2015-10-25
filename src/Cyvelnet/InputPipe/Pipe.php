<?php

namespace Cyvelnet\InputPipe;

use BadMethodCallException;
use Cyvelnet\InputPipe\Contracts\PipeProcessorContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class PipeProcessor
 *
 * @package Cyvelnet\InputPipe
 */
class Pipe implements PipeProcessorContract
{

    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var array
     */
    private $pipes;

    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @var string
     */
    protected $default_pipes = '';

    /**
     * Pipe constructor.
     *
     * @param array $data
     * @param array $pipes
     */
    public function __construct(array $data, array $pipes)
    {
        $this->data = $data;
        $this->pipes = $pipes;
    }

    /**
     * apply pipes too all inputs
     *
     * @param string $pipes
     *
     * @return $this
     */
    public function all($pipes)
    {
        $this->default_pipes = $pipes;

        return $this;
    }

    /**
     * get the piped results.
     *
     * @return array
     */
    public function get()
    {
        $data = $this->data;

        foreach ($data as $key => &$value) {
            $this->processPipes($value, $key);
        }

        return $data;
    }

    /**
     * @param $data
     * @param $key
     */
    private function processPipes(&$data, $key)
    {
        $pipes = $this->parsePipes($key);

        foreach ($pipes as $pipe) {

            if ($pipe[0] === '') {
                continue;
            }

            $method = 'pipe' . $pipe[0];
            $data = $this->$method($data, $pipe[1]);

        }
    }


    /**
     * @param $extension
     */
    public function addExtensions(array $extension)
    {
        if ($extension) {
            $keys = array_map('snake_case', array_keys($extension));

            $extensions = array_combine($keys, array_values($extension));
        }

        $this->extensions = array_merge($this->extensions, $extension);
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }


    /**
     * @param $attribute
     *
     * @return mixed
     */
    private function getValue($attribute)
    {
        return array_get($this->data, $attribute);
    }

    /**
     * @param $pipe
     * @param $parameters
     *
     * @return mixed
     */
    private function callPipeExtension($pipe, $parameters)
    {
        $callback = $this->extensions[$pipe];

        return call_user_func_array($callback, $parameters);

    }


    /**
     * @param $pipe
     *
     * @return array
     */
    private function parsePipes($pipe)
    {
        $pipesWithKeys = $this->mergeInputWithPipes();

        if (!array_key_exists($pipe, $pipesWithKeys)) {
            return false;
        }

        $pipes = [];

        $pipeRules = array_get($pipesWithKeys, $pipe);

        $pipeSegments = explode('|', $pipeRules);
        foreach ($pipeSegments as $key => $segment) {
            $parameters = [];

            if (strpos($segment, ':') !== false) {
                list($pipeRules, $parameters) = explode(':', $segment, 2);
                $parameters = $this->parseParameters($parameters);

                array_push($pipes, [studly_case(trim($pipeRules)), $parameters]);
            } else {
                array_push($pipes, [studly_case(trim($segment)), $parameters]);
            }
        }

        return $pipes;
    }

    /**
     * @param $parameters
     *
     * @return array
     */
    private function parseParameters($parameters)
    {
        return explode(',', $parameters);
    }


    // pipe handlers here

    /**
     * pipe inputs though trim pipe
     *
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeTrim($data, $parameters = [])
    {
        return trim($data);
    }

    /**
     * pipe inputs though snake case pipe
     *
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeSnake($data, $parameters = [])
    {
        return strtolower(preg_replace('/\s/', '', preg_replace('/(.)(?=[A-Z])/', '$1' . '_', $data)));
    }

    /**
     * pipe inputs though camel case pipe
     *
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeCamel($data, $parameters = [])
    {
        return camel_case($data);
    }

    /**
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeLower($data, $parameters = [])
    {
        return strtolower($data);
    }

    /**
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeUpper($data, $parameters = [])
    {
        return strtoupper($data);
    }

    /**
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeUcword($data, $parameters = [])
    {
        return ucwords($data);
    }

    /**
     * pipe inputs though slug pipe
     *
     * @param $data
     * @param array $parameters
     *
     * @return string
     */
    public function pipeSlug($data, $parameters = [])
    {
        return trim(strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', trim($data))));
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $method string
     * @param $arguments array
     *
     * @return mixed
     */
    function __call($method, $arguments)
    {
        $pipe = snake_case(substr($method, 4));

        if (isset($this->extensions[$pipe])) {
            return $this->callPipeExtension($pipe, $arguments);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }

    /**
     * @return array
     */
    private function mergeInputWithPipes()
    {
        $pipesWithKeys = [];

        $pipes = $this->mergeWithDefaultPipes();

        $index = 0;
        foreach ($this->data as $key => $value) {
            $element = array_slice($pipes, $index);
            $pipesWithKeys[$key] = reset($element);
            $index++;
        }

        return $pipesWithKeys;
    }

    private function mergeWithDefaultPipes()
    {
        $pipes = $this->pipes;

        $keys = array_keys($this->data);

        // fill all undefined input keys with empty value
        $defaults = array_fill_keys($keys, '');

        // overwrite $default with existing data
        $pipes = array_merge($defaults, $pipes);

        foreach ($this->data as $key => $data) {
            $pipes[$key] = rtrim("{$this->default_pipes}|$pipes[$key]", '|');
        }

        return $pipes;
    }


}
