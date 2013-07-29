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
     * @param string $field
     * @return bool
     */
    public function hasField($field)
    {
        return isset($this->fields[$field]);
    }

    /**
     * @param string $field One of the DateTimeField constants.
     * @return mixed The value for this field.
     * @throws DateTimeParseException If the field is not present in this set.
     */
    public function getField($field)
    {
        if ($this->hasField($field)) {
            return $this->fields[$field];
        }

        throw new DateTimeParseException(sprintf('Field %s is not present in this set', $field));
    }

    /**
     * @param string $field
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getOptionalField($field, $defaultValue)
    {
        return $this->hasField($field) ? $this->fields[$field] : $defaultValue;
    }
}
