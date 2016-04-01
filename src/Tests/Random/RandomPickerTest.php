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

        $elementCount = rand(9, 99);

        for ($i = 1; $i <= $elementCount; $i++) {
            $elements["test $i"] = rand(1, 99);
        }

        $totalWeight = array_sum($elements);
        $randValues = range(1, $totalWeight);

        $this->shufflePreserveKeys($elements);
        shuffle($randValues); // we *do* want keys to be reassigned here

        $randomNumberGenerator = function() use ($randValues, & $index) {
            return $randValues[$index];
        };

        $picker = new RandomPicker($randomNumberGenerator);
        $picker->addElements($elements);

        $results = [];

        foreach ($elements as $element => $weight) {
            $results[$element] = 0;
        }

        for ($index = 0; $index < $totalWeight; $index++) {
            $element = $picker->getRandomElement();
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

    /**
     * Shuffles an associative array, preserving keys.
     *
     * @param $array
     */
    private function shufflePreserveKeys(array & $array) {
        $keys = array_keys($array);

        shuffle($keys);

        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }

        $array = $result;
    }
}
