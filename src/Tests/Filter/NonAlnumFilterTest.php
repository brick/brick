<?php

namespace Brick\Tests\Filter;

use Brick\Filter\NonAlnumFilter;

/**
 * Tests for class NonAlnumFilter.
 */
class NonAlnumFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFilter
     *
     * @param string $value    The value to filter.
     * @param string $expected The expected filtered value.
     */
    public function testFilter($value, $expected)
    {
        $filter = new NonAlnumFilter();
        $this->assertSame($expected, $filter->filter($value));
    }

    /**
     * @return array
     */
    public function providerFilter()
    {
        return [
            ['', ''],
            ['123', '123'],
            ['aBC', 'aBC'],
            ['12-AB-34 cd', '12AB34cd'],
        ];
    }
}
