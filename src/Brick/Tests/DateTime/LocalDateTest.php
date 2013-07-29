<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;

/**
 * Unit tests for class LocalDate.
 */
class LocalDateTest extends \PHPUnit_Framework_TestCase
{
    private $minDate;
    private $maxDate;

    private $minValidEpochDay;
    private $maxValidEpochDay;

    public function setUp()
    {
        $this->minDate = LocalDate::minDate();
        $this->maxDate = LocalDate::maxDate();

        $this->minValidEpochDay = $this->minDate->toEpochDay();
        $this->maxValidEpochDay = $this->maxDate->toEpochDay();
    }

    public function testFactory()
    {
        $date = LocalDate::of(2007, 7, 15);

        $this->assertEquals(2007, $date->getYear());
        $this->assertEquals(7, $date->getMonth());
        $this->assertEquals(15, $date->getDay());
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactory29FebNonLeap()
    {
        LocalDate::of(2007, 2, 29);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactory31Apr()
    {
        LocalDate::of(2007, 4, 31);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryDayTooLow()
    {
        LocalDate::of(2007, 1, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryDayTooHigh()
    {
        LocalDate::of(2007, 1, 32);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryMonthTooLow()
    {
        LocalDate::of(2007, 0, 1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryMonthTooHigh()
    {
        LocalDate::of(2007, 13, 1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryYearTooLow()
    {
        LocalDate::of(~PHP_INT_MAX, 1, 1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryYearTooHigh()
    {
        LocalDate::of(PHP_INT_MAX, 1, 1);
    }

    public function testFactoryOfYearDayNonLeap()
    {
        $date = LocalDate::of(2007, 1, 1);
        for ($i = 1; $i < 365; $i++) {
            $this->assertTrue(LocalDate::ofYearDay(2007, $i)->isEqualTo($date));
            $date = $date->plusDays(1);
        }
    }

    public function testFactoryOfYearDayLeap()
    {
        $date = LocalDate::of(2008, 1, 1);
        for ($i = 1; $i < 366; $i++) {
            $this->assertTrue(LocalDate::ofYearDay(2008, $i)->isEqualTo($date));
            $date = $date->plusDays(1);
        }
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryOfYearDay366NonLeap()
    {
        LocalDate::ofYearDay(2007, 366);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryOfYearDayWithDayTooLow()
    {
        LocalDate::ofYearDay(2007, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryOfYearDayWithDayTooHigh()
    {
        LocalDate::ofYearDay(2007, 367);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryOfYearDayWithYearTooLow()
    {
        LocalDate::ofYearDay(~ PHP_INT_MAX, 1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testFactoryOfYearDayWithYearTooHigh()
    {
        LocalDate::ofYearDay(PHP_INT_MAX, 1);
    }

    public function testFactoryOfEpochDay()
    {
        $date_0000_01_01 = -678941 - 40587;

        $this->assertTrue(LocalDate::ofEpochDay(0)->isEqualTo(LocalDate::of(1970, 1, 1)));
        $this->assertTrue(LocalDate::ofEpochDay($date_0000_01_01)->isEqualTo(LocalDate::of(0, 1, 1)));
        $this->assertTrue(LocalDate::ofEpochDay($date_0000_01_01 - 1)->isEqualTo(LocalDate::of(-1, 12, 31)));
        $this->assertTrue(LocalDate::ofEpochDay($this->minValidEpochDay)->isEqualTo(LocalDate::of(LocalDate::MIN_YEAR, 1, 1)));
        $this->assertTrue(LocalDate::ofEpochDay($this->maxValidEpochDay)->isEqualTo(LocalDate::of(LocalDate::MAX_YEAR, 12, 31)));

        $test = LocalDate::of(0, 1, 1);
        for ($i = $date_0000_01_01; $i < 700000; $i++) {
            $this->assertTrue(LocalDate::ofEpochDay($i)->isEqualTo($test));
            $test = $test->plusDays(1);
        }

        $test = LocalDate::of(0, 1, 1);
        for ($i = $date_0000_01_01; $i > -2000000; $i--) {
            $this->assertTrue(LocalDate::ofEpochDay($i)->isEqualTo($test));
            $test = $test->minusDays(1);
        }
    }
}
