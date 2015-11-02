<?php

namespace Brick\Type;

/**
 * Represents an immutable, UTF-8 string.
 * Requires the mbstring extension.
 */
class UnicodeString
{
    /**
     * Internal encoding of the string. This class is meant to support UTF-8 only.
     *
     * @const string
     */
    const ENCODING = 'UTF-8';

    /**
     * @var string[]
     */
    protected static $encodings = [];

    /**
     * The string data.
     *
     * @var string
     */
    protected $string;

    /**
     * The string length.
     *
     * @var int
     */
    protected $length;

    /**
     * Class constructor.
     *
     * @param mixed $string
     * @param string $encoding
     * @throws \InvalidArgumentException
     */
    public function __construct($string, $encoding = self::ENCODING)
    {
        if (is_null($string)) {
            $string = 'null';
        } elseif (is_bool($string)) {
            $string = $string ? 'true' : 'false';
        } elseif (is_int($string) || is_float($string)) {
            $string = (string) $string;
        } elseif (is_object($string)) {
            if (! method_exists($string, '__toString')) {
                throw new \InvalidArgumentException(
                    sprintf('Object of class %s cannot be converted to String', get_class($string))
                );
            }
            $string = (string) $string;
        } elseif (! is_string($string)) {
            throw new \InvalidArgumentException('Cannot convert a variable of type ' . gettype($string) . ' to String');
        }

        if (! self::checkEncoding($encoding)) {
            throw new \InvalidArgumentException('Unsupported encoding: ' . $encoding);
        }

        if (! mb_check_encoding($string, $encoding)) {
            throw new \InvalidArgumentException('String is not encoded in ' . $encoding);
        }

        if ($encoding != self::ENCODING) {
            $string = mb_convert_encoding($string, self::ENCODING, $encoding);
        }

        $string = \Normalizer::normalize($string);

        $this->string = $string;
        $this->length = mb_strlen($string, self::ENCODING);
    }

    /**
     * @param string $encoding
     * @return bool
     */
    protected static function checkEncoding($encoding)
    {
        if (count(self::$encodings) == 0) {
            self::buildEncodings();
        }

        return array_key_exists(strtolower($encoding), self::$encodings);
    }

    /**
     * @return void
     */
    protected static function buildEncodings()
    {
        $encodings = mb_list_encodings();

        foreach ($encodings as $encoding) {
            self::addEncoding($encoding);

            foreach (mb_encoding_aliases($encoding) as $encoding) {
                self::addEncoding($encoding);
            }
        }
    }

    /**
     * @param string $encoding
     * @return void
     */
    protected static function addEncoding($encoding)
    {
        if ($encoding == 'pass' || $encoding == 'none') {
            // These encodings are actually not accepted by mbstring.
            return;
        }

        $encoding = strtolower($encoding);
        self::$encodings[$encoding] = null;
    }

    /**
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * @param int $index
     * @return \Brick\Type\UnicodeString
     */
    public function charAt($index)
    {
        $char = mb_substr($this->string, $index, 1, self::ENCODING);
        return new UnicodeString($char);
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return \Brick\Type\UnicodeString
     */
    public function concat(UnicodeString $string)
    {
        return new UnicodeString($this->string . $string->string);
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return int
     */
    public function compareTo(UnicodeString $string)
    {
        // @todo
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return int
     */
    public function compareToIgnoreCase(UnicodeString $string)
    {
        // @todo
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function contains(UnicodeString $string)
    {
        return is_int(strpos($this->string, $string->string));
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function containsIgnoreCase(UnicodeString $string)
    {
        return $this->toLowerCase()->contains($string->toLowerCase());
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function endsWith(UnicodeString $string)
    {
        if ($string->length > $this->length) {
            return false;
        }

        return $this->substring(- $string->length)->equals($string);
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function equals(UnicodeString $string)
    {
        return $this->string === $string->string;
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function equalsIgnoreCase(UnicodeString $string)
    {
        return $this->toLowerCase()->equals($string->toLowerCase());
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @param int $fromIndex
     * @return int
     */
    public function indexOf(UnicodeString $string, $fromIndex = 0)
    {
        $index = mb_strpos($this->string, $string->string, $fromIndex, self::ENCODING);

        return ($index === false) ? -1 : $index;
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @param int $fromIndex
     * @return int
     */
    public function lastIndexOf(UnicodeString $string, $fromIndex = 0)
    {
        $index = mb_strrpos($this->string, $string->string, $fromIndex, self::ENCODING);

        return ($index === false) ? -1 : $index;
    }

    /**
     * @param \Brick\Type\UnicodeString $regex
     * @return bool
     */
    public function matches(UnicodeString $regex)
    {
        return (bool) preg_match($regex->string, $this->string);
    }

    /**
     * @param \Brick\Type\UnicodeString $target
     * @param \Brick\Type\UnicodeString $replacement
     * @return \Brick\Type\UnicodeString
     */
    public function replace(UnicodeString $target, UnicodeString $replacement)
    {
        return new UnicodeString(str_replace($target, $replacement, $this->string));
    }

    /**
     * @param \Brick\Type\UnicodeString $regex
     * @return \Brick\Type\UnicodeString[]
     */
    public function split(UnicodeString $regex)
    {
        $strings = preg_split($regex->string, $this->string);
        $function = function ($string) {
            return new UnicodeString($string);
        };

        return array_map($function, $strings);
    }

    /**
     * @param \Brick\Type\UnicodeString $string
     * @return bool
     */
    public function startsWith(UnicodeString $string)
    {
        if ($string->length > $this->length) {
            return false;
        }

        return $this->substring(0, $string->length)->equals($string);
    }

    /**
     * @param int $start
     * @param int|null $length
     * @return \Brick\Type\UnicodeString
     */
    public function substring($start, $length = null)
    {
        $string = mb_substr($this->string, $start, $length, self::ENCODING);
        return new UnicodeString($string);
    }

    /**
     * @return \Brick\Type\UnicodeString
     */
    public function toLowerCase()
    {
        return new UnicodeString(mb_convert_case($this->string, MB_CASE_LOWER, self::ENCODING));
    }

    /**
     * @return \Brick\Type\UnicodeString
     */
    public function toUpperCase()
    {
        return new UnicodeString(mb_convert_case($this->string, MB_CASE_UPPER, self::ENCODING));
    }

    /**
     * @return \Brick\Type\UnicodeString
     */
    public function trim()
    {
        return new UnicodeString(trim($this->string));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}
