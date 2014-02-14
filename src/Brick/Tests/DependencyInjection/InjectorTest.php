<?php

namespace Brick\Tests\DependencyInjection;

use Brick\DependencyInjection\InjectionPolicy\NullPolicy;
use Brick\DependencyInjection\Injector;
use Brick\DependencyInjection\ValueResolver\DefaultValueResolver;

class InjectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanInjectPrivateMethod()
    {
        $injector = new Injector(new DefaultValueResolver(), new NullPolicy());

        $object = $injector->instantiate(PrivateConstructor::class, [
            'foo' => 'Foo',
            'bar' => 'Bar'
        ]);

        $this->assertSame('Foo', $object->foo);
        $this->assertSame('Bar', $object->bar);
    }
}

class PrivateConstructor
{
    public $foo;
    public $bar;

    private function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
