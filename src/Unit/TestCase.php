<?php

declare(strict_types=1);

namespace Brick\Unit;

/**
 * Base class for unit tests, providing a method to get the StubBuilder.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Returns a StubBuilder for the given class.
     *
     * @param string $className
     *
     * @return MockBuilder
     */
    protected function getMockObjectBuilder(string $className) : MockBuilder
    {
        return new MockBuilder($this, $className);
    }
}
