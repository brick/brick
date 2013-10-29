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

    public function testWithYear()
    {
        $this->assertSame(2001, YearMonth::of(2000, 1)->withYear(2001)->getYear());
    }

    public function testWithMonth()
    {
        $this->assertSame(12, YearMonth::of(2000, 11)->withMonth(12)->getMonth());
    }

    public function testAtDay()
    {
        $this->assertSame('2001-02-03', YearMonth::of(2001, 02)->atDay(3)->toString());
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
