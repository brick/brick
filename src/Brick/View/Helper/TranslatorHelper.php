<?php

namespace Brick\View\Helper;

use Brick\Translation\Translator;
use Brick\Locale\Locale;

/**
 * Translator view helper.
 */
trait TranslatorHelper
{
    /**
     * @var \Brick\Translation\Translator|null
     */
    private $translator;

    /**
     * @Brick\DependencyInjection\Annotation\Inject
     *
     * @param \Brick\Translation\Translator $translator
     * @return void
     */
    final public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $key
     * @param array $parameters
     * @param \Brick\Locale\Locale|null $locale
     * @return string
     * @throws \RuntimeException
     */
    final public function translate($key, array $parameters = [], Locale $locale = null)
    {
        if (! $this->translator) {
            throw new \RuntimeException('No translator has been registered');
        }

        return $this->translator->translate($key, $parameters, $locale);
    }
}
