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
     * @param callable|null $rand The random number generator. Optional.
     *
     * @return mixed
     *
     * @throws \RuntimeException If no elements have been added.
     */
    public function getRandomElement(callable $rand = null)
    {
        if ($rand === null) {
            $rand = 'mt_rand';
        }

        if ($this->totalWeight !== 0) {
            $value = $rand(1, $this->totalWeight);

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
