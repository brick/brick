<?php

namespace Brick\Unit;

/**
 * Base class for unit tests, providing a method to get the StubBuilder.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns a StubBuilder for the given class.
     *
     * @param  string      $className
     * @return MockBuilder
     */
    protected function getMockObjectBuilder($className)
    {
        return new MockBuilder($this, $className);
    }
}
