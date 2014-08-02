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
 * Unit tests for class LocalTime.
 */
class LocalDateTimeTest extends \PHPUnit_Framework_TestCase
{
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
     * @dataProvider plusSecondsFromZeroProvider
     *
     * @param int       $seconds
     * @param LocalDate $date
     * @param int       $hour
     * @param int       $min
     * @param int       $sec
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
    public function plusSecondsFromZeroProvider()
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

    public function plusSecondsNoChangeOneDay()
    {
        $base = LocalDate::of(2007, 7, 15);
        $t = $base->atTime(LocalTime::of(12, 30, 40))->plusSeconds(24 * 60 * 60);
        $this->assertTrue($t->getDate()->isEqualTo($base->plusDays(1)));
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
     * @dataProvider minusSecondsFromZeroProvider
     *
     * @param int       $seconds
     * @param LocalDate $date
     * @param int       $hour
     * @param int       $min
     * @param int       $sec
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
    public function minusSecondsFromZeroProvider()
    {
        $tests = array();

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
     * @dataProvider providerToEpochSecond
     *
     * @param integer $y           The year.
     * @param integer $m           The month.
     * @param integer $d           The day.
     * @param integer $h           The hours.
     * @param integer $i           The minutes.
     * @param integer $s           The second.
     * @param integer $offset      The time-zone offset in hours.
     * @param integer $epochSecond The expected epoch second.
     */
    public function testToEpochSecond($y, $m, $d, $h, $i, $s, $offset, $epochSecond)
    {
        $datetime = LocalDateTime::of($y, $m, $d, $h, $i, $s);
        $offset = TimeZoneOffset::ofHours($offset);

        $this->assertSame($epochSecond, $datetime->toEpochSecond($offset));
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
