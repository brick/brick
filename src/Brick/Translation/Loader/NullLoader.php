<?php

namespace Brick\Translation\Loader;

use Brick\Translation\Loader;
use Brick\Locale\Locale;

/**
 * Null loader, that returns no translations. Useful for testing.
 */
class NullLoader implements Loader
{
    /**
     * {@inheritdoc}
     */
    public function load(Locale $locale)
    {
        return [];
    }
}
