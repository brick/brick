<?php

namespace Brick\Json;

/**
 * Exception thrown when an error occurs during encoding/decoding in JSON format.
 */
class JsonException extends \RuntimeException
{
    /**
     * @var array
     */
    private static $errors = array(
        JSON_ERROR_DEPTH          => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR      => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX         => 'Syntax error',
        JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

    /**
     * @param int $errorCode
     */
    public function __construct($errorCode)
    {
        $message = isset(self::$errors[$errorCode]) ? self::$errors[$errorCode] : 'Unknown error';

        parent::__construct($message, $errorCode);
    }
}
