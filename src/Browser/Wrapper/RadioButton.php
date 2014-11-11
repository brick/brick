<?php

namespace Brick\Browser\Wrapper;
use Brick\Browser\By;

/**
 * A radio button.
 *
 * @todo handle html5 form attribute on radio elements when looking for radios to uncheck.
 */
class RadioButton extends ToggleButton
{
    /**
     * {@inheritdoc}
     */
    public function check()
    {
        $form = $this->getForm();
        if ($form) {
            foreach ($form->element->find(By::cssSelector('input[type="radio"]')) as $radio) {
                RadioButton::create($radio)->uncheck();
            }
        }

        parent::check();
    }
}
