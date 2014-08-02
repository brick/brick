<?php

namespace Brick\DateTime\Field;

use Brick\DateTime\DateTimeException;

/**
 * An immutable set of date-time fields with their associated value.
 */
class DateTimeFieldSet
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
     *
     * @return boolean
     */
    public function has($field)
    {
        return isset($this->fields[$field]);
    }

    /**
     * @param string $field One of the DateTimeField constants.
     *
     * @return mixed The value for this field.
     *
     * @throws DateTimeException If the field is not present in this set.
     */
    public function get($field)
    {
        if ($this->has($field)) {
            return $this->fields[$field];
        }

        throw new DateTimeException(sprintf('Field %s is not present in this set', $field));
    }
}
