<?php

namespace Brick;

/**
 * Helper class for error-related behaviours.
 */
class Exception extends \Exception
{
    /**
     * Sets up an error handler that triggers ErrorException
     */
    public static function setupErrorHandler()
    {
        // Handle non-fatal errors
        set_error_handler(function($level, $message, $file, $line) {
            // @ operator, continue the normal error handler
            if (error_reporting() == 0) {
                return false;
            }

            throw new \ErrorException($message, 0, $level, $file, $line);
        });

        // Handle assertions
        assert_options(ASSERT_ACTIVE,   true);
        assert_options(ASSERT_BAIL,     false);
        assert_options(ASSERT_WARNING,  false);
        assert_options(ASSERT_CALLBACK, function () {
            throw new \RuntimeException('Assertion failed'); // @todo AssertionFailedException
        });

        // Handle fatal errors.
        // There is not much we can do at this point, but we'll at least set the response code to 500.
        // @todo We must have an ob_start() call at the beginning of the application, or else the fatal error message will send the headers.
        // @todo This might not be mandatory if display_errors is off. Check this assumption.
        register_shutdown_function(function() {
            $e = error_get_last();

            if ($e && $e['type'] === E_ERROR) {
                while (ob_get_level()) {
                    ob_end_clean();
                }

                if (! headers_sent()) {
                    header('Content-Type: text/plain', true, 500);

                    echo 'Internal server error.' . PHP_EOL;
                    echo '======================' . PHP_EOL;
                    echo PHP_EOL;

                    if (self::isLocalIp()) {
                        echo $e['message'] . PHP_EOL;
                        echo PHP_EOL;
                        echo 'File: ' . $e['file'] . PHP_EOL;
                        echo 'Line: ' . $e['line'] . PHP_EOL;
                    } else {
                        echo 'We apologize for the problem.' . PHP_EOL;
                        echo 'Our enginers have been notified.' . PHP_EOL;
                        echo PHP_EOL;
                        echo 'Please use the back button of your browser and try again.' . PHP_EOL;
                    }
                }

                exit;
            }
        });
    }

    /**
     * Quick&dirty check whether the remote IP is local.
     *
     * @return bool
     */
    private static function isLocalIp()
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = explode('.', $_SERVER['REMOTE_ADDR']);

            return $ip[0] == '127' || $ip[0] == '192' && $ip[1] == '168';
        }

        return false;
    }
}
