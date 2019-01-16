<?php

declare(strict_types=1);

namespace Brick\Type;

/**
 * Represents an immutable, UTF-8 string.
 * Requires the mbstring extension.
 */
class UnicodeString
{
    /**
     * The string data.
     *
     * @var string
     */
    private $string;

    /**
     * The string length.
     *
     * @var int
     */
    private $length;

    /**
     * Private constructor.
     *
     * @param string   $string The normalized UTF-8 string.
     * @param int|null $length The length, or null if not known.
     */
    private function __construct(string $string, int $length = null)
    {
        $this->string = $string;

        if ($length === null) {
            $this->length = mb_strlen($string, 'UTF-8');
        } else {
            $this->length = $length;
        }
    }

    /**
     * Public factory method.
     *
     * @param string $string
     *
     * @return UnicodeString
     *
     * @throws \InvalidArgumentException
     */
    public static function of(string $string) : UnicodeString
    {
        if (! mb_check_encoding($string, 'UTF-8')) {
            throw new \InvalidArgumentException('String is not valid UTF-8');
        }

        $string = \Normalizer::normalize($string);
        $length = mb_strlen($string, 'UTF-8');

        return new UnicodeString($string, $length);
    }

    /**
     * @return int
     */
    public function length() : int
    {
        return $this->length;
    }

    /**
     * @param int $index
     *
     * @return UnicodeString
     */
    public function charAt(int $index) : UnicodeString
    {
        $char = mb_substr($this->string, $index, 1, 'UTF-8');

        return new UnicodeString($char, $char === '' ? 0 : 1);
    }

    /**
     * @param UnicodeString $string
     *
     * @return UnicodeString
     */
    public function concat(UnicodeString $string) : UnicodeString
    {
        return new UnicodeString($this->string . $string->string);
    }

    /**
     * @param UnicodeString $string
     *
     * @return int
     */
    public function compareTo(UnicodeString $string) : int
    {
        // @todo
    }

    /**
     * @param UnicodeString $string
     *
     * @return int
     */
    public function compareToIgnoreCase(UnicodeString $string) : int
    {
        // @todo
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function contains(UnicodeString $string) : bool
    {
        return strpos($this->string, $string->string) !== false;
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function containsIgnoreCase(UnicodeString $string) : bool
    {
        return $this->toLowerCase()->contains($string->toLowerCase());
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function endsWith(UnicodeString $string) : bool
    {
        if ($string->length > $this->length) {
            return false;
        }

        return $this->substring(- $string->length)->equals($string);
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function equals(UnicodeString $string) : bool
    {
        return $this->string === $string->string;
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function equalsIgnoreCase(UnicodeString $string) : bool
    {
        return $this->toLowerCase()->equals($string->toLowerCase());
    }

    /**
     * @param UnicodeString $string
     * @param int           $fromIndex
     *
     * @return int
     */
    public function indexOf(UnicodeString $string, int $fromIndex = 0) : int
    {
        $index = mb_strpos($this->string, $string->string, $fromIndex, 'UTF-8');

        return ($index === false) ? -1 : $index;
    }

    /**
     * @param UnicodeString $string
     * @param int           $fromIndex
     *
     * @return int
     */
    public function lastIndexOf(UnicodeString $string, int $fromIndex = 0) : int
    {
        $index = mb_strrpos($this->string, $string->string, $fromIndex, 'UTF-8');

        return ($index === false) ? -1 : $index;
    }

    /**
     * @param UnicodeString $regex
     *
     * @return bool
     */
    public function matches(UnicodeString $regex) : bool
    {
        return (bool) preg_match($regex->string, $this->string);
    }

    /**
     * @param UnicodeString $target
     * @param UnicodeString $replacement
     *
     * @return UnicodeString
     */
    public function replace(UnicodeString $target, UnicodeString $replacement) : UnicodeString
    {
        return new UnicodeString(str_replace($target, $replacement, $this->string));
    }

    /**
     * @param UnicodeString $regex
     *
     * @return UnicodeString[]
     */
    public function split(UnicodeString $regex) : array
    {
        return array_map(
            static function ($string) {
                return new UnicodeString($string);
            },
            preg_split($regex->string, $this->string)
        );
    }

    /**
     * @param UnicodeString $string
     *
     * @return bool
     */
    public function startsWith(UnicodeString $string) : bool
    {
        if ($string->length > $this->length) {
            return false;
        }

        return $this->substring(0, $string->length)->equals($string);
    }

    /**
     * @param int      $start
     * @param int|null $length
     *
     * @return UnicodeString
     */
    public function substring(int $start, int $length = null) : UnicodeString
    {
        $string = mb_substr($this->string, $start, $length, 'UTF-8');
        return new UnicodeString($string);
    }

    /**
     * @return UnicodeString
     */
    public function toLowerCase() : UnicodeString
    {
        return new UnicodeString(mb_convert_case($this->string, MB_CASE_LOWER, 'UTF-8'));
    }

    /**
     * @return UnicodeString
     */
    public function toUpperCase() : UnicodeString
    {
        return new UnicodeString(mb_convert_case($this->string, MB_CASE_UPPER, 'UTF-8'));
    }

    /**
     * @return UnicodeString
     */
    public function trim() : UnicodeString
    {
        return new UnicodeString(trim($this->string));
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->string;
    }
}
