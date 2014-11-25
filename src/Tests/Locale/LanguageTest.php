<?php

namespace Brick\Tests\Currency;

use Brick\Locale\Language;

/**
 * Unit tests for class Language.
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider accessorsProvider
     *
     * @param string      $alpha3B
     * @param string|null $alpha3T
     * @param string|null $alpha2
     * @param string      $englishName
     * @param string      $frenchName
     */
    public function testAccessors($alpha3B, $alpha3T, $alpha2, $englishName, $frenchName)
    {
        $language = Language::get($alpha3B);

        $this->assertSame($alpha3B, $language->getAlpha3BCode());
        $this->assertSame($alpha3T, $language->getAlpha3TCode());
        $this->assertSame($alpha2, $language->getAlpha2Code());
        $this->assertSame($englishName, $language->getEnglishName());
        $this->assertSame($frenchName, $language->getFrenchName());
    }

    /**
     * @return array
     */
    public function accessorsProvider()
    {
        return [
            ['fre', 'fre', 'fr', 'French', 'français'],
            ['eng', 'eng', 'en', 'English', 'anglais']
        ];
    }

    public function testGet()
    {
        $en = Language::get('en');
        $eng = Language::get('eng');

        $this->assertSame($en, $eng);
    }
}
