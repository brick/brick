<?php

namespace Brick\IdentityResolver;

/**
 * Exception thrown when the identity of an object cannot be determined.
*/
class IdentityResolutionException extends \Exception
{
    /**
     * @param object      $object
     * @param string|null $message
     *
     * @return IdentityResolutionException
     */
    public static function cannotResolveIdentity($object, $message = null)
    {
        $exceptionMessage = sprintf('Cannot resolve the identity of object %s', get_class($object));

        if ($message !== null) {
            $exceptionMessage .= ': ' . $message;
        }

        $exceptionMessage .= '.';

        return new self($exceptionMessage);
    }
}
