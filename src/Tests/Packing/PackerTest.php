<?php

namespace Brick\Tests\Packing;

use Brick\Packing\Packer;
use Brick\Packing\NullObjectPacker;

use PHPUnit\Framework\TestCase;

/**
 * Tests for class Packer.
 */
class PackerTest extends TestCase
{
    public function testRecursion()
    {
        $a = new A();
        $b = new B();

        $a->b = $b;
        $b->a = $a;

        $packer = new Packer(new NullObjectPacker());
        $c = $packer->pack($a);

        $this->assertTrue($c instanceof A);
        $this->assertTrue($c->b instanceof B);

        $this->assertFalse($c === $a);
        $this->assertFalse($c->b === $b);

        $this->assertTrue($c->b->a === $c);
    }
}

class A
{
    public $b;
}

class B
{
    public $a;
}
