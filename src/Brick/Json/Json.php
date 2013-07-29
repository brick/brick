<?php

namespace Brick\Json;

/**
 * Encodes and decodes variables in JSON format, throwing JsonException if case of failure.
 */
final class Json
{
    /**
     * This class is not meant to be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * @param mixed $variable
     *
     * @return string
     *
     * @throws JsonException
     */
    public static function encode($variable)
    {
        $result = @json_encode($variable);

        self::exceptionIfError();

        return $result;
    }

    /**
     * @param string  $string The JSON string to decode.
     * @param boolean $assoc  Whether to convert objects to associative arrays.
     *
     * @return mixed
     *
     * @throws JsonException
     */
    public static function decode($string, $assoc = false)
    {
        $result = @json_decode($string, $assoc);

        self::exceptionIfError();

        return $result;
    }

    /**
     * @return void
     *
     * @throws JsonException
     */
    private static function exceptionIfError()
    {
        $error = json_last_error();

        if ($error != JSON_ERROR_NONE) {
            throw new JsonException($error);
        }
    }
}
