<?php

namespace Brick\Tests\Random;

use Brick\Random\RandomPicker;

/**
 * Tests for class RandomPicker.
 */
class RandomPickerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerSeed
     *
     * @param int $seed
     */
    public function testUniformDistribution($seed)
    {
        srand($seed);

        $elements = [];

        $elementCount = rand(1, 99);

        for ($i = 0; $i < $elementCount; $i++) {
            $elements["test $i"] = rand(1, 99);
        }

        $totalWeight = array_sum($elements);
        $randValues = range(1, $totalWeight);

        shuffle($elements);
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

    /**
     * @return array
     */
    public function providerSeed()
    {
        for ($i = 0; $i < 10; $i++) {
            yield [$i];
        }
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
