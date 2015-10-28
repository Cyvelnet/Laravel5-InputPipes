<?php

use Mockery as m;

/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 10/23/2015
 * Time: 5:11 PM.
 */
class PipeTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * @param $data
     * @param $pipes
     *
     * @return \Cyvelnet\InputPipe\Pipe
     */
    private function instance($data, $pipes)
    {
        return (new \Cyvelnet\InputPipe\Factory())->make($data, $pipes);
    }

    /**
     * test trim pipe.
     */
    public function testTrimPipe()
    {
        $factory = $this->instance(['foo' => '    Foo   '], ['foo' => 'trim']);

        $this->assertEquals(['foo' => 'Foo'], $factory->get());
    }

    /**
     * test snake case pipe with data without space.
     */
    public function testSnakeCasePipeWithoutSpace()
    {
        $factory = $this->instance(['foo' => 'FooBar'], ['foo' => 'snake']);

        $this->assertEquals(['foo' => 'foo_bar'], $factory->get());
    }

    /**
     * test snake case pipe with data without space.
     */
    public function testSnakeCasePipeWithSpace()
    {
        $factory = $this->instance(['foo' => 'Foo Bar'], ['foo' => 'snake']);

        $this->assertEquals(['foo' => 'foo_bar'], $factory->get());
    }

    /**
     * test camel case pipe.
     */
    public function testCamelCasePipe()
    {
        $factory = $this->instance(['foo' => 'Foo Bar'], ['foo' => 'camel']);

        $this->assertEquals(['foo' => 'fooBar'], $factory->get());
    }

    /**
     * test lower case pipe.
     */
    public function testLowerCasePipe()
    {
        $factory = $this->instance(['foo' => 'Foo Bar'], ['foo' => 'lower']);

        $this->assertEquals(['foo' => 'foo bar'], $factory->get());
    }

    /**
     * test upper case pipe.
     */
    public function testUpperCasePipe()
    {
        $factory = $this->instance(['foo' => 'foo bar'], ['foo' => 'upper']);

        $this->assertEquals(['foo' => 'FOO BAR'], $factory->get());
    }

    /**
     * test ucword pipe.
     */
    public function testUcwordPipe()
    {
        $factory = $this->instance(['foo' => 'fOo bAr'], ['foo' => 'ucword']);

        $this->assertEquals(['foo' => 'FOo BAr'], $factory->get());
    }

    /**
     * test slug pipe.
     */
    public function testSlugPipe()
    {
        $factory = $this->instance(['foo' => 'fOo bAr'], ['foo' => 'slug']);

        $this->assertEquals(['foo' => 'foo-bar'], $factory->get());
    }

    /**
     * test multiple pipes.
     */
    public function testNestedPipes()
    {
        $factory = $this->instance(['foo' => '  fOo bAr  baz', 'bar' => ' baz'], ['foo' => 'trim|upper|camel', 'bar' => 'upper|trim']);

        $this->assertEquals(['foo' => 'fOOBARBAZ', 'bar' => 'BAZ'], $factory->get());
    }

    public function testDefaultPipes()
    {
        $factoryWithTrim = $this->instance(['foo' => '  fOo bAr  baz', 'bar' => ' baz'], ['foo' => 'trim|upper|camel', 'bar' => 'upper|trim']);

        $factoryWithAllTrim = $this->instance(['foo' => '  fOo bAr  baz', 'bar' => ' baz'], ['foo' => 'upper|camel', 'bar' => 'upper'])->all('trim');

        $this->assertEquals($factoryWithTrim->get(), $factoryWithAllTrim->get());
    }
}
