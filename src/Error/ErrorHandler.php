<?php

declare(strict_types=1);

namespace Brick\Error;

/**
 * Provides a global handler for unexpected errors and uncaught exceptions.
 */
class ErrorHandler
{
    /**
     * Sets up a global error handler.
     *
     * This handler catches PHP errors and throws ErrorException accordingly.
     *
     * Uncaught exceptions and fatal errors change the response status code to 500 Internal Server Error
     * (only if the headers have not yet been sent), and call an optional fatal error handler function that
     * can be used to output a human-friendly error page.
     *
     * The fatal error handler, if provided, will receive as sole argument the exception that has
     * triggered the condition: the uncaught exception, or an ErrorException describing a fatal error.
     *
     * @param callable|null $fatalErrorHandler The optional fatal error handler.
     *
     * @return void
     */
    public static function setup(?callable $fatalErrorHandler = null) : void
    {
        // Handle PHP errors and throw exceptions.
        set_error_handler(static function($level, $message, $file, $line) {
            if (error_reporting() === 0) {
                // @ operator, continue to the normal error handler.
                return false;
            }

            throw new \ErrorException($message, 0, $level, $file, $line);
        });

        if (! $fatalErrorHandler) {
            $fatalErrorHandler = static function($e) {
                if (ini_get('display_errors')) {
                    if (! headers_sent()) {
                        header('Content-Type: text/plain');
                    }

                    echo $e;
                }
            };
        }

        // Handle uncaught exceptions.
        set_exception_handler(static function($e) use ($fatalErrorHandler) {
            if (! headers_sent()) {
                http_response_code(500);
            }

            $fatalErrorHandler($e);

            exit(1);
        });

        // Handle fatal errors.
        register_shutdown_function(static function() use ($fatalErrorHandler) {
            $e = error_get_last();

            if ($e && $e['type'] === E_ERROR) {
                if (! headers_sent()) {
                    http_response_code(500);
                }

                $exception = new \ErrorException($e['message'], 0, $e['type'], $e['file'], $e['line']);
                $fatalErrorHandler($exception);

                exit(1);
            }
        });
    }
}
