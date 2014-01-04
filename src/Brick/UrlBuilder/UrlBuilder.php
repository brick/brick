<?php

namespace Brick\UrlBuilder;

use Brick\ObjectConverter\ObjectConverter;

/**
 * @todo should maybe be called UriBuilder (i, not l)
 */
class UrlBuilder
{
    /**
     * @var \Brick\ObjectConverter\ObjectConverter[]
     */
    private $objectConverters = [];

    /**
     * @param ObjectConverter $converter
     *
     * @return static
     */
    public function addObjectConverter(ObjectConverter $converter)
    {
        $this->objectConverters[] = $converter;

        return $this;
    }

    /**
     * Builds a URL with the given parameters.
     *
     * If the URL already contains query parameters, they will be merged, the parameters passed to the method
     * having precedence over the original query parameters.
     *
     * If any of the method parameters is an object, it will be replaced by its identity, as provided
     * by the ObjectIdentityResolver implementation.
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return string
     */
    public function buildUrl($url, array $parameters = [])
    {
        if (count($parameters)) {
            foreach ($parameters as $key => $value) {
                if (is_null($value)) {
                    unset($parameters[$key]);
                }
                if (is_object($value)) {
                    $parameters[$key] = $this->convertObject($value);
                }
            }
        }

        $pos = strpos($url, '?');
        if (is_int($pos)) {
            parse_str(substr($url, $pos + 1), $query);
            $parameters += $query;

            $url = substr($url, 0, $pos);
        }

        if (count($parameters)) {
            return $url . '?' . http_build_query($parameters);
        }

        return $url;
    }

    /**
     * @param object $object
     *
     * @return string|array
     *
     * @throws \RuntimeException
     */
    private function convertObject($object)
    {
        foreach ($this->objectConverters as $resolver) {
            $identity = $resolver->shrink($object);

            if ($identity !== null) {
                return $identity;
            }
        }

        throw new \RuntimeException('Cannot convert object ' . get_class($object));
    }
}
