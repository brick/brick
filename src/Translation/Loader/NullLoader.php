<?php

namespace Brick\Translation\Loader;

use Brick\Translation\TranslationLoader;

/**
 * Null loader, that returns no translations. Useful for testing.
 */
class NullLoader implements TranslationLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($locale)
    {
        return [];
    }
}
