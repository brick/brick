<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\YearMonth;

/**
 * Unit tests for class YearMonth.
 *
 * @todo the parser fails to break on extra chars at the end of the string
 */
class YearMonthTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $ym = YearMonth::of(2007, 7);

        $this->assertSame(2007, $ym->getYear());
        $this->assertSame(7, $ym->getMonth());
    }

    public function testParse()
    {
        $ym = YearMonth::parse('2011-02');

        $this->assertSame(2011, $ym->getYear());
        $this->assertSame(2, $ym->getMonth());
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testParseInvalidStringThrowsException()
    {
        YearMonth::parse('2010-01-01');
    }

    public function testToInteger()
    {
        $this->assertSame(2012 * 12 + 11, YearMonth::of(2012, 11)->toInteger());
    }

    public function testToString()
    {
        $this->assertSame('2013-09', YearMonth::of(2013, 9)->toString());
    }
}
