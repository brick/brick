<?php

namespace Brick\Tests\Currency;

use Brick\Locale\Locale;

/**
 * Unit tests for class Locale.
 */
class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseExceptionProvider
     * @expectedException \InvalidArgumentException
     *
     * @param string $locale
     */
    public function testParseException($locale)
    {
        Locale::parse($locale);
    }

    /**
     * @return array
     */
    public function parseExceptionProvider()
    {
        return [
            [''],
            ["\0"],
            ['-'],
            ['_'],
            ['#']
        ];
    }

    public function testIsEqualTo()
    {
        $a = Locale::create('en', 'GB');
        $b = Locale::create('EN', 'gb');

        $this->assertTrue($a->isEqualTo($b));
    }

    public function testNormalization()
    {
        $locale = Locale::parse('Fr-fr');

        $this->assertEquals('fr_FR', $locale->toString());
    }
}
