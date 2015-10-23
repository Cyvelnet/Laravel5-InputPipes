<?php

use Mockery as m;

class PipeFactoryTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testSingleExtend()
    {
        $factory = new \Cyvelnet\InputPipe\Factory();
        $fooClosure = function () {
        };

        $factory->extend('foo', $fooClosure);

        $pipe = $factory->make([], []);


        $this->assertEquals(['foo' => $fooClosure], $pipe->getExtensions());
    }

    public function testMultipleExtend()
    {
        $factory = new \Cyvelnet\InputPipe\Factory();

        $fooClosure = function () {
        };
        $barClosure = function () {
        };
        $bazClosure = function () {
        };

        $factory->extend('foo', $fooClosure);
        $factory->extend('bar', $barClosure);
        $factory->extend('baz', $bazClosure);
        $pipe = $factory->make([], []);

        $this->assertEquals(['foo' => $fooClosure, 'bar' => $barClosure, 'baz' => $bazClosure], $pipe->getExtensions());
    }

    public function testCustomPipe()
    {
        $factory = new \Cyvelnet\InputPipe\Factory();

        $factory->extra(function ($data, $pipes) {
            return new TestPipe($data, $pipes);
        });

        $this->assertEquals(['foo' => 'Bar'], $factory->make(['foo' => 'Bar'], ['foo'])->get());
        $this->assertEquals(['foo' => 'Bar', 'bar' => 'Baz'], $factory->make(['foo' => 'Bar', 'bar' => 'Baz'], ['foo', 'bar'])->get());
    }
}

class TestPipe extends \Cyvelnet\InputPipe\Pipe
{
    public function pipeFoo($data, $parameters = [])
    {
        return $data;
    }

    public function pipeBar($data, $parameters = [])
    {
        return $data;
    }
}
