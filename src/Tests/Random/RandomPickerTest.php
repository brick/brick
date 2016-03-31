<?php

namespace Brick\Tests\Random;

use Brick\Random\RandomPicker;

/**
 * Tests for class RandomPicker.
 */
class RandomPickerTest extends \PHPUnit_Framework_TestCase
{
    public function testUniformDistribution()
    {
        $elements = [
            'a' => 22,
            'b' => 75,
            'c' => 91,
            'd' => 12,
            'e' => 3,
            'f' => 62,
            'g' => 55,
            'h' => 89
        ];

        $totalWeight = array_sum($elements);

        srand(0);
        shuffle($elements);

        $randValues = range(1, $totalWeight);

        srand(123);
        shuffle($randValues);

        $picker = new RandomPicker();
        $picker->addElements($elements);

        $results = [];

        foreach ($elements as $element => $weight) {
            $results[$element] = 0;
        }

        for ($i = 0; $i < $totalWeight; $i++) {
            $element = $picker->getRandomElement(function() use ($randValues, $i) {
                return $randValues[$i];
            });

            $results[$element]++;
        }

        $this->assertSame($elements, $results);
    }

    public function testDefaultGeneratorSingleElement()
    {
        $picker = new RandomPicker();
        $picker->addElement('test', 123);

        $this->assertSame('test', $picker->getRandomElement());
        $this->assertSame('test', $picker->getRandomElement());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidWeight()
    {
        $picker = new RandomPicker();
        $picker->addElement('test', 0);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEmptyPicker()
    {
        $picker = new RandomPicker();
        $picker->getRandomElement();
    }
}
