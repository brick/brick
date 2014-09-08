<?php

namespace Brick\Translation\Loader;

use Brick\Translation\TranslationLoader;
use Brick\Locale\Locale;

/**
 * Null loader, that returns no translations. Useful for testing.
 */
class NullLoader implements TranslationLoader
{
    /**
     * {@inheritdoc}
     */
    public function load(Locale $locale)
    {
        return [];
    }
}
