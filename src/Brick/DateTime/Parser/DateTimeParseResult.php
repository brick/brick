<?php

namespace Brick\DateTime\Parser;

/**
 * Result of a date-time string parsing.
 */
class DateTimeParseResult
{
    /**
     * @var array
     */
    private $fields;

    /**
     * Class constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Returns whether this result has at least one value for the given field.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasField($name)
    {
        return isset($this->fields[$name]) && $this->fields[$name];
    }

    /**
     * Returns the first value parsed for the given field.
     *
     * @param string $name One of the field constants.
     *
     * @return string The value for this field.
     *
     * @throws DateTimeParseException If the field is not present in this set.
     */
    public function getField($name)
    {
        $value = $this->getOptionalField($name);

        if ($value === '') {
            throw new DateTimeParseException(sprintf('Field %s is not present in the parsed result.', $name));
        }

        return $value;
    }

    /**
     * Returns the first value for the given field, or an empty string if not present.
     *
     * @param string $name
     *
     * @return string
     */
    public function getOptionalField($name)
    {
        if (isset($this->fields[$name])) {
            if ($this->fields[$name]) {
                $result = array_shift($this->fields[$name]);

                return $result;
            }
        }

        return '';
    }
}
