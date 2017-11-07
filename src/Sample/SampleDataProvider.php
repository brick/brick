<?php

namespace Brick\Sample;

/**
 * Interface that must be implemented by sample data providers.
 */
interface SampleDataProvider
{
    /**
     * Returns the name of this data provider.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Executes the data provider.
     *
     * @return void
     */
    public function run() : void;
}
