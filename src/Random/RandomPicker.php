<?php

declare(strict_types=1);

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
     * Whether the weights array is sorted by descending weight.
     *
     * Sorting is not necessary for the algorithm to return correct results,
     * but it greatly improves performance for large arrays. Sorting is performed
     * just-in-time when calling getRandomElement().
     *
     * @var bool
     */
    private $isSorted = true;

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
    public function addElement($value, int $weight) : void
    {
        if ($weight < 1) {
            throw new \InvalidArgumentException('Weight must be a positive integer.');
        }

        $this->elements[] = $value;
        $this->weights[] = $weight;
        $this->totalWeight += $weight;
        $this->isSorted = false;
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
    public function addElements(array $elements) : void
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
        if (! $this->isSorted) {
            arsort($this->weights);
            $this->isSorted = true;
        }

        if ($this->totalWeight !== 0) {
            $value = random_int(1, $this->totalWeight);

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
