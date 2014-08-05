<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalTime;
use Brick\DateTime\ZonedDateTime;
use Brick\DateTime\TimeZone;

/**
 * Unit tests for class ZonedDateTime.
 */
class ZonedDateTimeTest extends AbstractTestCase
{
    /**
     * @dataProvider providerOfTimestamp
     *
     * @param string $formattedDatetime
     * @param string $timeZone
     */
    public function testOfTimestamp($formattedDatetime, $timeZone)
    {
        $timestamp = 1000000000;
        $zonedDateTime = ZonedDateTime::ofTimestamp($timestamp, TimeZone::of($timeZone));

        $this->assertEquals($timestamp, $zonedDateTime->getInstant()->getTimestamp());
        $this->assertEquals($formattedDatetime, $zonedDateTime->getDateTime()->toString());
    }

    /**
     * @return array
     */
    public function providerOfTimestamp()
    {
        return [
            ['2001-09-09T01:46:40', 'UTC'],
            ['2001-09-08T18:46:40', 'America/Los_Angeles']
        ];
    }

    /**
     * @dataProvider providerParse
     *
     * @param string $text   The string to parse.
     * @param string $date   The expected date string.
     * @param string $time   The expected time string.
     * @param string $offset The expected time-zone offset.
     * @param string $zone   The expected time-zone, should be the same as offset when no region is specified.
     */
    public function testParse($text, $date, $time, $offset, $zone)
    {
        $zonedDateTime = ZonedDateTime::parse($text);

        $this->assertSame($date, (string) $zonedDateTime->getDate());
        $this->assertSame($time, (string) $zonedDateTime->getTime());
        $this->assertSame($offset, (string) $zonedDateTime->getTimeZoneOffset());
        $this->assertSame($zone, (string) $zonedDateTime->getTimeZone());
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['2001-02-03T01:02Z', '2001-02-03', '01:02', 'Z', 'Z'],
            ['2001-02-03T01:02:03Z', '2001-02-03', '01:02:03', 'Z', 'Z'],
            ['2001-02-03T01:02:03.456Z', '2001-02-03', '01:02:03.456', 'Z', 'Z'],
            ['2001-02-03T01:02-03:00', '2001-02-03', '01:02', '-03:00', '-03:00'],
            ['2001-02-03T01:02:03+04:00', '2001-02-03', '01:02:03', '+04:00', '+04:00'],
            ['2001-02-03T01:02:03.456+12:34:56', '2001-02-03', '01:02:03.456', '+12:34:56', '+12:34:56'],
            ['2001-02-03T01:02+00:00[Europe/London]', '2001-02-03', '01:02', 'Z', 'Europe/London'],
            ['2001-02-03T01:02:03+00:00[Europe/London]', '2001-02-03', '01:02:03', 'Z', 'Europe/London'],
            ['2001-02-03T01:02:03.456+00:00[Europe/London]', '2001-02-03', '01:02:03.456', 'Z', 'Europe/London']
        ];
    }

    public function testCreateFromLocalDate()
    {
        $date = LocalDate::of(2012, 6, 30);
        $datetime = ZonedDateTime::createFromDate($date, TimeZone::of('America/Los_Angeles'));
        $this->assertTrue($datetime->getDate()->isEqualTo($date));
        $this->assertEquals(1341039600, $datetime->getInstant()->getTimestamp());
    }

    public function testCreateFromDateAndTime()
    {
        $date = LocalDate::of(2012, 6, 30);
        $time = LocalTime::of(12, 34, 56);
        $datetime = ZonedDateTime::createFromDateAndTime($date, $time, TimeZone::of('America/Los_Angeles'));
        $this->assertTrue($datetime->getDate()->isEqualTo($date));
        $this->assertTrue($datetime->getTime()->isEqualTo($time));
        $this->assertEquals(1341084896, $datetime->getInstant()->getTimestamp());
    }

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
