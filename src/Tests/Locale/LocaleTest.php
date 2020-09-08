<?php

namespace Brick\Tests\Locale;

use Brick\Locale\Locale;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class Locale.
 */
class LocaleTest extends TestCase
{
    /**
     * @dataProvider parseExceptionProvider
     *
     * @param string $locale
     */
    public function testParseException($locale)
    {
        $this->expectException(\InvalidArgumentException::class);
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
