<?php

declare(strict_types=1);

namespace Brick\Sample;

use Brick\Di\Injector;
use Doctrine\ORM\EntityManager;

/**
 * This class coordinates the calls to the individual sample data providers.
 */
class SampleDataDispatcher
{
    /**
     * The class names of the sample data providers to run.
     *
     * @var string[]
     */
    private $providers = [];

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Brick\Di\Injector
     */
    private $injector;

    /**
     * Class constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Brick\Di\Injector          $injector
     */
    public function __construct(EntityManager $em, Injector $injector)
    {
        $this->em = $em;
        $this->injector = $injector;
    }

    /**
     * Adds a sample data provider.
     * The data providers will be run in the order in which they have been added.
     *
     * @param string $className The sample data provider class name.
     *
     * @return void
     */
    public function addProvider(string $className) : void
    {
        $this->providers[] = $className;
    }

    /**
     * Runs all sample data providers.
     *
     * @param callable|null $callback A function that will be called with the name of each provider as a parameter.
     *
     * @return void
     */
    public function run(?callable $callback = null) : void
    {
        $this->em->beginTransaction();

        foreach ($this->providers as $provider) {
            $this->em->clear();

            /** @var $provider \Brick\Sample\SampleDataProvider */
            $provider = $this->injector->instantiate($provider);

            if ($callback) {
                $callback($provider->getName());
            }

            $provider->run();
            $this->em->flush();
        }

        $this->em->commit();
    }
}
