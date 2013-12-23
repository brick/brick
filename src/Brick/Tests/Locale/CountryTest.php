<?php

namespace Brick\Tests\Currency;

use Brick\Locale\Country;
use Brick\Locale\Currency;

/**
 * Unit tests for class Country.
 */
class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider accessorsProvider
     */
    public function testAccessors($code, $currency, $name)
    {
        $country = Country::of($code);

        $this->assertEquals($code, $country->getCode());
        $this->assertEquals($name, $country->getName());
        $this->assertEquals($currency, $country->getCurrency()->getCurrencyCode());
    }

    /**
     * @return array
     */
    public function accessorsProvider()
    {
        return [
            ['ES', 'EUR', 'SPAIN'],
            ['FR', 'EUR', 'FRANCE'],
            ['GB', 'GBP', 'UNITED KINGDOM'],
            ['IE', 'EUR', 'IRELAND'],
            ['JP', 'JPY', 'JAPAN'],
            ['US', 'USD', 'UNITED STATES']
        ];
    }

    public function testGetAvailableCountries()
    {
        $countries = Country::getAvailableCountries();

        $this->assertGreaterThan(1, count($countries));

        foreach ($countries as $country) {
            $this->assertTrue($country instanceof Country);
        }
    }

    public function testGetInstance()
    {
        $this->assertSame(Country::of('US'), Country::of('US'));
    }

    public function testIsEqualTo()
    {
        $original = Country::of('US');
        $copy = unserialize(serialize($original));

        /** @var $copy Country */
        $this->assertNotSame($original, $copy);
        $this->assertTrue($copy->isEqualTo($original));
    }
}
