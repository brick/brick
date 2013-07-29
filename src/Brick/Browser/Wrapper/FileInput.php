<?php

namespace Brick\Browser\Wrapper;

use Brick\Browser\Element;
use Brick\Browser\Exception\UnexpectedElementException;

/**
 * A file input.
 */
class FileInput extends FormControl
{
    /**
     * @param Element $element
     * @return FileInput
     * @throws UnexpectedElementException
     */
    public static function create(Element $element)
    {
        if (! $element->is('input')) {
            throw new UnexpectedElementException('Expected input element, got ' . $element->getTagName());
        }

        $type = strtolower($element->getAttribute('type'));
        if ($type != 'file') {
            throw new UnexpectedElementException('Expected input type=file, got ' . $type);
        }

        return new FileInput($element);
    }

    /**
     * @param string $path
     * @return void
     */
    public function selectFile($path)
    {
        $this->element->setAttribute('value', $path);
    }
}
