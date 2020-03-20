<?php

namespace Brick\Random
{
    class TestData
    {
        /**
         * @var bool
         */
        public static $useTestData;

        /**
         * @var array
         */
        public static $randomValues;

        /**
         * @var int
         */
        public static $index;
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    function random_int(int $min, int $max) : int
    {
        if (TestData::$useTestData) {
            return TestData::$randomValues[TestData::$index];
        }

        return \random_int($min, $max);
    }
}

namespace Brick\Tests\Random
{
    use Brick\Random\RandomPicker;
    use Brick\Random\TestData;

    use PHPUnit\Framework\TestCase;

    /**
     * Tests for class RandomPicker.
     */
    class RandomPickerTest extends TestCase
    {
        protected function setUp() : void
        {
            TestData::$useTestData = false;
        }

        /**
         * @dataProvider providerSeed
         *
         * @param int $seed
         */
        public function testUniformDistribution($seed)
        {
            TestData::$useTestData = true;

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

            TestData::$randomValues = $randValues;

            $picker = new RandomPicker();
            $picker->addElements($elements);

            $results = [];

            foreach ($elements as $element => $weight) {
                $results[$element] = 0;
            }

            for ($index = 0; $index < $totalWeight; $index++) {
                TestData::$index = $index;
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

        public function testInvalidWeight()
        {
            $picker = new RandomPicker();

            $this->expectException(\InvalidArgumentException::class);
            $picker->addElement('test', 0);
        }

        public function testEmptyPicker()
        {
            $picker = new RandomPicker();

            $this->expectException(\RuntimeException::class);
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
}