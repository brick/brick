<?php

namespace Brick\Random;

/**
 * Picks a random element from a number of weighted element.
 *
 * Elements can be of any type. Weights must be positive integers.
 */
class RandomPicker
{
    /**
     * @var callable
     */
    private $randomNumberGenerator;

    /**
     * The elements to be randomized.
     *
     * @var array
     */
    private $elements = [];

    /**
     * The weights for each element, using common keys.
     *
     * @var array
     */
    private $weights = [];

    /**
     * The sum of all the weights.
     *
     * @var int
     */
    private $totalWeight = 0;

    /**
     * RandomPicker constructor.
     *
     * The random number generator is a function accepting two integers, and returning
     * a (pseudo) random integer that is contained between these two numbers.
     *
     * This defaults to PHP's mt_rand() function.
     *
     * @param callable|null $randomNumberGenerator The random number generator.
     */
    public function __construct(callable $randomNumberGenerator = null)
    {
        $this->randomNumberGenerator = $randomNumberGenerator ?: 'mt_rand';
    }

    /**
     * Adds a single element.
     *
     * @param mixed $value
     * @param int   $weight
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the weight is not a positive integer.
     */
    public function addElement($value, $weight)
    {
        $weight = (int) $weight;

        if ($weight < 1) {
            throw new \InvalidArgumentException('Weight must be a positive integer.');
        }

        $this->elements[] = $value;
        $this->weights[] = $weight;
        $this->totalWeight += $weight;
    }

    /**
     * Adds an associative array of elements.
     *
     * The keys are elements, the values are weights.
     *
     * @param array $elements
     *
     * @return void
     *
     * @throws \InvalidArgumentException If a weight is not a positive integer.
     */
    public function addElements(array $elements)
    {
        foreach ($elements as $value => $weight) {
            $this->addElement($value, $weight);
        }
    }

    /**
     * @return mixed
     *
     * @throws \RuntimeException If no elements have been added.
     */
    public function getRandomElement()
    {
        if ($this->totalWeight !== 0) {
            $randomNumberGenerator = $this->randomNumberGenerator;
            $value = $randomNumberGenerator(1, $this->totalWeight);

            foreach ($this->weights as $key => $weight) {
                $value -= $weight;

                if ($value <= 0) {
                    return $this->elements[$key];
                }
            }
        }

        throw new \RuntimeException('No elements have been added.');
    }
}
