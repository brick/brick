<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDateTime;
use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalTime;
use Brick\DateTime\TimeZone;
use Brick\DateTime\TimeZoneOffset;
use Brick\DateTime\ZonedDateTime;
use Brick\DateTime\Year;

/**
 * Unit tests for class LocalDateTime.
 */
class LocalDateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param integer       $y  The expected year.
     * @param integer       $m  The expected month.
     * @param integer       $d  The expected day.
     * @param integer       $h  The expected hour.
     * @param integer       $i  The expected minute.
     * @param integer       $s  The expected second.
     * @param integer       $n  The expected nano.
     * @param LocalDateTime $dt The date-time to test.
     */
    private function assertLocalDateTimeEquals($y, $m, $d, $h, $i, $s, $n, LocalDateTime $dt)
    {
        $this->assertSame($y, $dt->getYear());
        $this->assertSame($m, $dt->getMonth());
        $this->assertSame($d, $dt->getDay());
        $this->assertSame($h, $dt->getHour());
        $this->assertSame($i, $dt->getMinute());
        $this->assertSame($s, $dt->getSecond());
        $this->assertSame($n, $dt->getNano());
    }

    /**
     * @dataProvider providerParse
     *
     * @param string  $t The text to parse.
     * @param integer $y The expected year.
     * @param integer $m The expected month.
     * @param integer $d The expected day.
     * @param integer $h The expected hour.
     * @param integer $i The expected minute.
     * @param integer $s The expected second.
     * @param integer $n The expected nano.
     */
    public function testParse($t, $y, $m, $d, $h, $i, $s, $n)
    {
        $this->assertLocalDateTimeEquals($y, $m, $d, $h, $i, $s, $n, LocalDateTime::parse($t));
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['0999-02-28T12:34', 999, 2, 28, 12, 34, 0, 0],
            ['2014-02-28T12:34', 2014, 2, 28, 12, 34, 0, 0],
            ['1999-12-31T01:02:03', 1999, 12, 31, 1, 2, 3, 0],
            ['2012-02-29T23:43:10.1234', 2012, 2, 29, 23, 43, 10, 123400000]
        ];
    }

    /**
     * @dataProvider providerParseInvalidStringThrowsException
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     *
     * @param string $text
     */
    public function testParseInvalidStringThrowsException($text)
    {
        LocalDateTime::parse($text);
    }

    /**
     * @return array
     */
    public function providerParseInvalidStringThrowsException()
    {
        return [
            [' 2014-02-28T12:34'],
            ['2014-02-28T12:34 '],
            ['2014-2-27T12:34'],
            ['2014-222-27T12:34'],
            ['2014-02-2T12:34'],
            ['2014-02-222T12:34'],
            ['2014-02-28T1:34'],
            ['2014-02-28T111:34'],
            ['2014-02-28T12:3'],
            ['2014-02-28T12:345'],
            ['2014-02-28T12:34:5'],
            ['2014-02-28T12:34:567'],
            ['2014-02-28T12:34:56.'],
            ['2014-02-28T12:34:56.1234567890'],
            ['201X-02-27T12:34:56.123'],
            ['2014-0X-27T12:34:56.123'],
            ['2014-02-2XT12:34:56.123'],
            ['2014-02-27T1X:34:56.123'],
            ['2014-02-27T12:3X:56.123'],
            ['2014-02-27T12:34:5X.123'],
            ['2014-02-27T12:34:56.12X'],
        ];
    }

    /**
     * @dataProvider providerParseInvalidDateTimeThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param string $text
     */
    public function testParseInvalidDateTimeThrowsException($text)
    {
        LocalDateTime::parse($text);
    }

    /**
     * @return array
     */
    public function providerParseInvalidDateTimeThrowsException()
    {
        return [
            ['2014-00-15T12:34'],
            ['2014-13-15T12:34'],
            ['2014-02-00T12:34'],
            ['2014-02-29T12:34'],
            ['2014-03-32T12:34'],
            ['2014-01-01T60:00:00'],
            ['2014-01-01T00:60:00'],
            ['2014-01-01T00:00:60'],
        ];
    }

    public function testPlusHoursOne()
    {
        $d = LocalDate::of(2007, 7, 15);
        $t = $d->atTime(LocalTime::midnight());

        for ($i = 0; $i < 50; $i++) {
            $t = $t->plusHours(1);

            if (($i + 1) % 24 == 0) {
                $d = $d->plusDays(1);
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals(($i + 1) % 24, $t->getHour());
        }
    }

    public function testPlusHoursFromZero()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $base->getDate()->minusDays(3);
        $t = LocalTime::of(21, 0);

        for ($i = -50; $i < 50; $i++) {
            $dt = $base->plusHours($i);
            $t = $t->plusHours(1);

            if ($t->getHour() == 0) {
                $d = $d->plusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testPlusHoursFromOne()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::of(1, 0));
        $d = $base->getDate()->minusDays(3);
        $t = LocalTime::of(22, 0);

        for ($i = -50; $i < 50; $i++) {
            $dt = $base->plusHours($i);
            $t = $t->plusHours(1);

            if ($t->getHour() == 0) {
                $d = $d->plusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testPlusMinutesOne()
    {
        $t = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $t->getDate();

        $hour = 0;
        $min = 0;

        for ($i = 0; $i < 70; $i++) {
            $t = $t->plusMinutes(1);
            $min++;

            if ($min == 60) {
                $hour++;
                $min = 0;
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals($hour, $t->getHour());
            $this->assertEquals($min, $t->getMinute());
        }
    }

    public function testPlusMinutesFromZero()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $base->getDate()->minusDays(1);
        $t = LocalTime::of(22, 49);

        for ($i = -70; $i < 70; $i++) {
            $dt = $base->plusMinutes($i);
            $t = $t->plusMinutes(1);

            if ($t->isEqualTo(LocalTime::midnight())) {
                $d = $d->plusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testPlusMinutesNoChangeOneDay()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::of(12, 30, 40));
        $t = $base->plusMinutes(24 * 60);
        $this->assertTrue($t->getDate()->isEqualTo($base->getDate()->plusDays(1)));
    }

    public function testPlusSecondsOne()
    {
        $t = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $t->getDate();

        $hour = 0;
        $min = 0;
        $sec = 0;

        for ($i = 0; $i < 3700; $i++) {
            $t = $t->plusSeconds(1);
            $sec++;

            if ($sec == 60) {
                $min++;
                $sec = 0;
            }
            if ($min == 60) {
                $hour++;
                $min = 0;
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals($hour, $t->getHour());
            $this->assertEquals($min, $t->getMinute());
            $this->assertEquals($sec, $t->getSecond());
        }
    }

    /**
     * @dataProvider providerPlusSecondsFromZero
     *
     * @param integer   $seconds
     * @param LocalDate $date
     * @param integer   $hour
     * @param integer   $min
     * @param integer   $sec
     */
    public function testPlusSecondsFromZero($seconds, LocalDate $date, $hour, $min, $sec)
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $t = $base->plusSeconds($seconds);

        $this->assertTrue($t->getDate()->isEqualTo($date));
        $this->assertEquals($hour, $t->getHour());
        $this->assertEquals($min, $t->getMinute());
        $this->assertEquals($sec, $t->getSecond());
    }

    /**
     * @return array
     */
    public function providerPlusSecondsFromZero()
    {
        $tests = [];

        $delta = 30;
        $i = -3660;
        $date = LocalDate::of(2007, 7, 14);
        $hour = 22;
        $min = 59;
        $sec = 0;

        while ($i <= 3660) {
            $tests[] = [$i, $date, $hour, $min, $sec];

            $i+= $delta;
            $sec += $delta;

            if ($sec >= 60) {
                $min++;
                $sec -= 60;

                if ($min == 60) {
                    $hour++;
                    $min = 0;

                    if ($hour == 24) {
                        $hour = 0;
                    }
                }

                if ($i == 0) {
                    $date = $date->plusDays(1);
                }
            }
        }

        return $tests;
    }

    public function testPlusSecondsNoChangeOneDay()
    {
        $base = LocalDate::of(2007, 7, 15);
        $t = $base->atTime(LocalTime::of(12, 30, 40))->plusSeconds(24 * 60 * 60);
        $this->assertTrue($t->getDate()->isEqualTo($base->plusDays(1)));
    }

    /**
     * @dataProvider providerPlusNanos
     *
     * @param string  $dateTime         The base date-time string.
     * @param integer $nanosToAdd       The nanoseconds to add.
     * @param string  $expectedDateTime The expected resulting date-time string.
     */
    public function testPlusNanos($dateTime, $nanosToAdd, $expectedDateTime)
    {
        $actualDateTime = LocalDateTime::parse($dateTime)->plusNanos($nanosToAdd);
        $this->assertSame($expectedDateTime, (string) $actualDateTime);
    }

    /**
     * @return array
     */
    public function providerPlusNanos()
    {
        return [
            ['2014-12-31T23:59:58.5', 1500000000, '2015-01-01T00:00'],
            ['2000-03-01T00:00', -1, '2000-02-29T23:59:59.999999999'],
            ['2000-01-01T00:00:01', -1999999999, '1999-12-31T23:59:59.000000001']
        ];
    }

    public function testMinusHoursOne()
    {
        $d = LocalDate::of(2007, 7, 15);
        $t = $d->atTime(LocalTime::midnight());

        for ($i = 0; $i < 50; $i++) {
            $t = $t->minusHours(1);

            if ($i % 24 == 0) {
                $d = $d->minusDays(1);
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals((((-$i + 23) % 24) + 24) % 24, $t->getHour());
        }
    }

    public function testMinusHoursFromZero()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $base->getDate()->plusDays(2);
        $t = LocalTime::of(3, 0);

        for ($i = -50; $i < 50; $i++) {
            $dt = $base->minusHours($i);
            $t = $t->minusHours(1);

            if ($t->getHour() == 23) {
                $d = $d->minusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testMinusHoursFromOne()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::of(1, 0));
        $d = $base->getDate()->plusDays(2);
        $t = LocalTime::of(4, 0);

        for ($i = -50; $i < 50; $i++) {
            $dt = $base->minusHours($i);
            $t = $t->minusHours(1);

            if ($t->getHour() == 23) {
                $d = $d->minusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testMinusMinutesOne()
    {
        $t = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $t->getDate()->minusDays(1);

        $hour = 0;
        $min = 0;

        for ($i = 0; $i < 70; $i++) {
            $t = $t->minusMinutes(1);
            $min--;

            if ($min == -1) {
                $hour--;
                $min = 59;

                if ($hour == -1) {
                    $hour = 23;
                }
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals($hour, $t->getHour());
            $this->assertEquals($min, $t->getMinute());
        }
    }

    public function testMinusMinutesFromZero()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $base->getDate()->minusDays(1);
        $t = LocalTime::of(22, 49);

        for ($i = 70; $i > -70; $i--) {
            $dt = $base->minusMinutes($i);
            $t = $t->plusMinutes(1);

            if ($t->isEqualTo(LocalTime::midnight())) {
                $d = $d->plusDays(1);
            }

            $this->assertTrue($dt->getDate()->isEqualTo($d));
            $this->assertTrue($dt->getTime()->isEqualTo($t));
        }
    }

    public function testMinusMinutesNoChangeOneDay()
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::of(12, 30, 40));
        $t = $base->minusMinutes(24 * 60);
        $this->assertTrue($t->getDate()->isEqualTo($base->getDate()->minusDays(1)));
    }

    public function testMinusSecondsOne()
    {
        $t = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $d = $t->getDate()->minusDays(1);

        $hour = 0;
        $min = 0;
        $sec = 0;

        for ($i = 0; $i < 3700; $i++) {
            $t = $t->minusSeconds(1);
            $sec--;

            if ($sec == -1) {
                $min--;
                $sec = 59;

                if ($min == -1) {
                    $hour--;
                    $min = 59;

                    if ($hour == -1) {
                        $hour = 23;
                    }
                }
            }

            $this->assertTrue($t->getDate()->isEqualTo($d));
            $this->assertEquals($hour, $t->getHour());
            $this->assertEquals($min, $t->getMinute());
            $this->assertEquals($sec, $t->getSecond());
        }
    }

    /**
     * @dataProvider providerMinusSecondsFromZero
     *
     * @param integer   $seconds
     * @param LocalDate $date
     * @param integer   $hour
     * @param integer   $min
     * @param integer   $sec
     */
    public function testMinusSecondsFromZero($seconds, LocalDate $date, $hour, $min, $sec)
    {
        $base = LocalDate::of(2007, 7, 15)->atTime(LocalTime::midnight());
        $t = $base->minusSeconds($seconds);

        $this->assertTrue($t->getDate()->isEqualTo($date));
        $this->assertEquals($hour, $t->getHour());
        $this->assertEquals($min, $t->getMinute());
        $this->assertEquals($sec, $t->getSecond());
    }

    /**
     * @return array
     */
    public function providerMinusSecondsFromZero()
    {
        $tests = [];

        $delta = 30;
        $i = 3660;
        $date = LocalDate::of(2007, 7, 14);
        $hour = 22;
        $min = 59;
        $sec = 0;

        while ($i >= -3660) {
            $tests[] = [$i, $date, $hour, $min, $sec];

            $i-= $delta;
            $sec += $delta;

            if ($sec >= 60) {
                $min++;
                $sec -= 60;

                if ($min == 60) {
                    $hour++;
                    $min = 0;

                    if ($hour == 24) {
                        $hour = 0;
                    }
                }

                if ($i == 0) {
                    $date = $date->plusDays(1);
                }
            }
        }

        return $tests;
    }

    /**
     * @dataProvider providerMinusNanos
     *
     * @param string  $dateTime         The base date-time string.
     * @param integer $nanosToSubtract  The nanoseconds to subtract.
     * @param string  $expectedDateTime The expected resulting date-time string.
     */
    public function testMinusNanos($dateTime, $nanosToSubtract, $expectedDateTime)
    {
        $actualDateTime = LocalDateTime::parse($dateTime)->minusNanos($nanosToSubtract);
        $this->assertSame($expectedDateTime, (string) $actualDateTime);
    }

    /**
     * @return array
     */
    public function providerMinusNanos()
    {
        return [
            ['2016-01-01T00:00', 50000000, '2015-12-31T23:59:59.95'],
            ['2000-02-29T23:59:59.999999999', -1, '2000-03-01T00:00'],
            ['1999-12-31T23:59:59.000000001', -2199999999, '2000-01-01T00:00:01.2']
        ];
    }

    public function testAtTimeZone()
    {
        $t = LocalDateTime::of(2008, 6, 30, 11, 30);
        $tz = TimeZone::of('Europe/Paris');

        $this->assertTrue($t->atTimeZone($tz)->isEqualTo(ZonedDateTime::of($t, $tz)));
    }

    public function testAtTimeZoneOffset()
    {
        $t = LocalDateTime::of(2008, 6, 30, 11, 30);
        $tz = TimeZoneOffset::ofHours(2);

        $this->assertTrue($t->atTimeZone($tz)->isEqualTo(ZonedDateTime::of($t, $tz)));
    }

    /**
     * @return array
     */
    public function providerToEpochSecond()
    {
        return [
            [1837, 12, 16, 13, 32, 59, 2, -4166857621],
            [1923, 1, 30, 0, 59, 1, -3, -1480708859],
            [1969, 12, 31, 23, 59, 59, 0, -1],
            [1969, 12, 31, 23, 59, 59, -1, 3599],
            [1969, 12, 31, 23, 59, 59, 1, -3601],
            [1970, 1, 1, 0, 0, 0, 0, 0],
            [1970, 1, 1, 0, 0, 0, 1, -3600],
            [1970, 1, 1, 0, 0, 0, -1, 3600],
            [1970, 1, 1, 0, 0, 1, 0, 1],
            [1980, 2, 28, 12, 23, 34, -7, 320613814],
            [2022, 11, 30, 1, 7, 9, 6, 1669748829],
            [2236, 3, 15, 20, 0, 0, 1, 8400567600],
        ];
    }

    public function testComparisonsLocalDateTime()
    {
        $dates = [
            LocalDate::of(Year::MIN_YEAR, 1, 1),
            LocalDate::of(Year::MIN_YEAR, 12, 31),
            LocalDate::of(-1, 1, 1),
            LocalDate::of(-1, 12, 31),
            LocalDate::of(0, 1, 1),
            LocalDate::of(0, 12, 31),
            LocalDate::of(1, 1, 1),
            LocalDate::of(1, 12, 31),
            LocalDate::of(2008, 1, 1),
            LocalDate::of(2008, 2, 29),
            LocalDate::of(2008, 12, 31),
            LocalDate::of(Year::MAX_YEAR, 1, 1),
            LocalDate::of(Year::MAX_YEAR, 12, 31)
        ];

        $times = [
            LocalTime::of(0, 0, 0),
            LocalTime::of(0, 0, 59),
            LocalTime::of(0, 59, 0),
            LocalTime::of(0, 59, 59),
            LocalTime::of(12, 0, 0),
            LocalTime::of(12, 0, 59),
            LocalTime::of(12, 59, 0),
            LocalTime::of(12, 59, 59),
            LocalTime::of(23, 0, 0),
            LocalTime::of(23, 0, 59),
            LocalTime::of(23, 59, 0),
            LocalTime::of(23, 59, 59)
        ];

        $localDateTimes = [];

        foreach ($dates as $date) {
            foreach ($times as $time) {
                $localDateTimes[] = LocalDateTime::ofDateTime($date, $time);
            }
        }

        $this->doTestComparisonsLocalDateTime($localDateTimes);
    }

    /**
     * @param LocalDateTime[] $localDateTimes
     */
    public function doTestComparisonsLocalDateTime(array $localDateTimes)
    {
        for ($i = 0; $i < count($localDateTimes); $i++) {
            $a = $localDateTimes[$i];
            for ($j = 0; $j < count($localDateTimes); $j++) {
                $b = $localDateTimes[$j];
                $message = $a . ' <=> ' . $b;
                if ($i < $j) {
                    $this->assertLessThan(0, $a->compareTo($b), $message);
                    $this->assertTrue($a->isBefore($b), $message);
                    $this->assertFalse($a->isAfter($b), $message);
                    $this->assertFalse($a->isEqualTo($b), $message);
                } else if ($i > $j) {
                    $this->assertGreaterThan(0, $a->compareTo($b), $message);
                    $this->assertFalse($a->isBefore($b), $message);
                    $this->assertTrue($a->isAfter($b), $message);
                    $this->assertFalse($a->isEqualTo($b), $message);
                } else {
                    $this->assertEquals(0, $a->compareTo($b), $message);
                    $this->assertFalse($a->isBefore($b), $message);
                    $this->assertFalse($a->isAfter($b), $message);
                    $this->assertTrue($a->isEqualTo($b), $message);
                }
            }
        }
    }
}
