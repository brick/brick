<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalTime;
use Brick\DateTime\ZonedDateTime;
use Brick\DateTime\TimeZone;

/**
 * Unit tests for class ZonedDateTime.
 */
class ZonedDateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testCreate()
    {
        $dateTimeZone = new \DateTimeZone('UTC');
        $dateTime = new \DateTime('@0', $dateTimeZone);
        $zonedDateTime = ZonedDateTime::fromDateTime($dateTime);

        $this->assertEquals($dateTime, $zonedDateTime->toDateTime());
        $this->assertEquals($dateTimeZone, $zonedDateTime->getTimeZone()->toDateTimeZone());
    }

    /**
     * @dataProvider createFromTimestampDataProvider
     *
     * @param string $formattedDatetime
     * @param string $timeZone
     * @return void
     */
    public function testCreateFromTimestamp($formattedDatetime, $timeZone)
    {
        $timestamp = 1e9;
        $zonedDateTime = ZonedDateTime::ofTimestamp($timestamp, TimeZone::of($timeZone));

        $this->assertEquals($timestamp, $zonedDateTime->getInstant()->getEpochSecond());
        $this->assertEquals($formattedDatetime, $zonedDateTime->getDateTime()->toString());
    }

    /**
     * @return array
     */
    public function createFromTimestampDataProvider()
    {
        return [
            ['2001-09-09T01:46:40', 'UTC'],
            ['2001-09-08T18:46:40', 'America/Los_Angeles']
        ];
    }

    public function testCreateFromLocalDate()
    {
        $date = LocalDate::of(2012, 6, 30);
        $datetime = ZonedDateTime::createFromDate($date, TimeZone::of('America/Los_Angeles'));
        $this->assertTrue($datetime->getDate()->isEqualTo($date));
        $this->assertEquals(1341039600, $datetime->getInstant()->getEpochSecond());
    }

    public function testCreateFromDateAndTime()
    {
        $date = LocalDate::of(2012, 6, 30);
        $time = LocalTime::of(12, 34, 56);
        $datetime = ZonedDateTime::createFromDateAndTime($date, $time, TimeZone::of('America/Los_Angeles'));
        $this->assertTrue($datetime->getDate()->isEqualTo($date));
        $this->assertTrue($datetime->getTime()->isEqualTo($time));
        $this->assertEquals(1341084896, $datetime->getInstant()->getEpochSecond());
    }

    /**
     * @return void
     */
    public function testChangeTimeZone()
    {
        $timezone1 = TimeZone::of('UTC');
        $timezone2 = TimeZone::of('America/Los_Angeles');

        $datetime1 = ZonedDateTime::ofTimestamp(1e9, $timezone1);
        $datetime2 = $datetime1->withTimeZoneSameInstant($timezone2);

        $this->assertEquals($timezone1, $datetime1->getTimezone());
        $this->assertEquals($timezone2, $datetime2->getTimezone());
        $this->assertEquals('2001-09-08T18:46:40', $datetime2->getDateTime()->toString());

        $datetime2 = $datetime1->withTimeZoneSameLocal($timezone2);

        $this->assertEquals($timezone1, $datetime1->getTimezone());
        $this->assertEquals($timezone2, $datetime2->getTimezone());
        $this->assertEquals('2001-09-09T01:46:40', $datetime2->getDateTime()->toString());
    }
}
