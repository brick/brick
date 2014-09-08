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
     * @Brick\Di\Annotation\Inject
     *
     * @param \Brick\Translation\Translator $translator
     *
     * @return void
     */
    final public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Brick\Translation\Translator
     *
     * @throws \RuntimeException
     */
    final public function getTranslator()
    {
        if (! $this->translator) {
            throw new \RuntimeException('No translator has been registered');
        }

        return $this->translator;
    }

    /**
     * @param string      $key
     * @param Locale|null $locale
     *
     * @return string
     */
    final public function translate($key, Locale $locale = null)
    {
        return $this->getTranslator()->translate($key, $locale);
    }
}
