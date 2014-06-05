<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalDateRange;

/**
 * Unit tests for class LocalDateRange.
 */
class LocalDateRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndAccessors()
    {
        $from = LocalDate::of(2010, 1, 1);
        $to = LocalDate::of(2011, 1, 1);

        $range = new LocalDateRange($from, $to);

        $this->assertSame($from, $range->getFrom());
        $this->assertSame($to, $range->getTo());
    }

    public function testParse()
    {
        $range = LocalDateRange::parse('2008-01-01/2009-12-31');

        $this->assertTrue($range->getFrom()->isEqualTo(LocalDate::of(2008, 1, 1)));
        $this->assertTrue($range->getTo()->isEqualTo(LocalDate::of(2009, 12, 31)));
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testParseInvalidRangeThrowsException()
    {
        LocalDateRange::parse('2010-01-01');
    }

    public function testToString()
    {
        $range = new LocalDateRange(
            LocalDate::of(2008, 12, 31),
            LocalDate::of(2011, 1, 1)
        );

        $this->assertSame('2008-12-31/2011-01-01', $range->toString());
        $this->assertSame('2008-12-31/2011-01-01', (string) $range);
    }

    public function testIterator()
    {
        $from = LocalDate::of(2013, 12, 30);
        $to   = LocalDate::of(2014, 1, 2);

        $range = new LocalDateRange($from, $to);

        for ($i = 0; $i < 2; $i++) { // Test twice to test iterator rewind
            $expected = $from;
            foreach ($range as $date) {
                $this->assertTrue($date->isEqualTo($expected));
                $expected = $expected->plusDays(1);
            }
        }
    }

    /**
     * @dataProvider countProvider
     */
    public function testCount($range, $count)
    {
        $this->assertCount($count, LocalDateRange::parse($range));
    }

    /**
     * @return array
     */
    public function countProvider()
    {
        return [
            ['2010-01-01/2010-01-01', 1],
            ['2013-12-30/2014-01-02', 4],
            ['2013-01-01/2013-12-31', 365],
            ['2012-01-01/2012-12-31', 366],
            ['2000-01-01/2020-01-01', 7306],
            ['1900-01-01/2000-01-01', 36525]
        ];
    }
}
