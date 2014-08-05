<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\YearMonth;

/**
 * Unit tests for class YearMonth.
 */
class YearMonthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param integer   $year      The expected year.
     * @param integer   $month     The expected month.
     * @param YearMonth $yearMonth The year-month to test.
     */
    private function assertYearMonthEquals($year, $month, YearMonth $yearMonth)
    {
        $this->assertSame($year, $yearMonth->getYear());
        $this->assertSame($month, $yearMonth->getMonth());
    }

    public function testOf()
    {
        $this->assertYearMonthEquals(2007, 7, YearMonth::of(2007, 7));
    }

    /**
     * @dataProvider providerParse
     *
     * @param string  $text  The text to parse.
     * @param integer $year  The expected year.
     * @param integer $month The expected month.
     */
    public function testParse($text, $year, $month)
    {
        $this->assertYearMonthEquals($year, $month, YearMonth::parse($text));
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['2011-02', 2011, 02],
            ['0908-11', 908, 11],
            ['-0050-01', -50, 1],
            ['-12345-02', -12345, 2],
            ['12345-03', 12345, 3]
        ];
    }

    /**
     * @dataProvider providerParseInvalidStringThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param string $string
     */
    public function testParseInvalidStringThrowsException($string)
    {
        YearMonth::parse($string);
    }

    /**
     * @return array
     */
    public function providerParseInvalidStringThrowsException()
    {
        return [
            ['999-01'],
            ['-999-01'],
            ['2010-01-01'],
            [' 2010-10'],
            ['2010-10 '],
            ['2010.10']
        ];
    }

    /**
     * @dataProvider providerGetLengthOfMonth
     *
     * @param integer $year   The year.
     * @param integer $month  The month.
     * @param integer $length The expected length of month.
     */
    public function testGetLengthOfMonth($year, $month, $length)
    {
        $this->assertSame($length, YearMonth::of($year, $month)->getLengthOfMonth());
    }

    /**
     * @return array
     */
    public function providerGetLengthOfMonth()
    {
        return [
            [1999, 1, 31],
            [2000, 1, 31],
            [1999, 2, 28],
            [2000, 2, 29],
            [1999, 3, 31],
            [2000, 3, 31],
            [1999, 4, 30],
            [2000, 4, 30],
            [1999, 5, 31],
            [2000, 5, 31],
            [1999, 6, 30],
            [2000, 6, 30],
            [1999, 7, 31],
            [2000, 7, 31],
            [1999, 8, 31],
            [2000, 8, 31],
            [1999, 9, 30],
            [2000, 9, 30],
            [1999, 10, 31],
            [2000, 10, 31],
            [1999, 11, 30],
            [2000, 11, 30],
            [1999, 12, 31],
            [2000, 12, 31]
        ];
    }

    /**
     * @dataProvider providerGetLengthOfYear
     *
     * @param integer $year   The year.
     * @param integer $month  The month.
     * @param integer $length The expected length of year.
     */
    public function testGetLengthOfYear($year, $month, $length)
    {
        $this->assertSame($length, YearMonth::of($year, $month)->getLengthOfYear());
    }

    /**
     * @return array
     */
    public function providerGetLengthOfYear()
    {
        return [
            [1999, 1, 365],
            [2000, 1, 366],
            [2001, 1, 365]
        ];
    }

    public function testWithYear()
    {
        $this->assertYearMonthEquals(2001, 5, YearMonth::of(2000, 5)->withYear(2001));
    }

    public function testWithMonth()
    {
        $this->assertYearMonthEquals(2000, 12, YearMonth::of(2000, 1)->withMonth(12));
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
