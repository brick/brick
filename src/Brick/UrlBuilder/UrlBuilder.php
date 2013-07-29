<?php

namespace Brick\UrlBuilder;

use Brick\IdentityResolver\IdentityResolver;

/**
 * @todo should maybe be called UriBuilder (i, or l)
 */
class UrlBuilder
{
    /**
     * @var IdentityResolver
     */
    private $resolver;

    /**
     * @param IdentityResolver $resolver
     */
    public function __construct(IdentityResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Builds a URL with the given parameters.
     *
     * If the URL already contains query parmeters, they will be merged, the parameters passed to the method
     * having precedence over the original query parameters.
     *
     * If any of the method parameters is an object, it will be replaced by its identity, as provided
     * by the ObjectIdentityResolver implementation.
     *
     * @param string $url
     * @param array  $parameters
     * @return string
     */
    public function buildUrl($url, array $parameters = array())
    {
        if (count($parameters)) {
            foreach ($parameters as & $parameter) {
                if (is_object($parameter)) {
                    $parameter = $this->resolver->getIdentity($parameter);
                }
            }
        }

        $pos = strpos($url, '?');
        if (is_int($pos)) {
            parse_str(substr($url, $pos + 1), $query);
            $parameters = array_merge($query, $parameters);

            $url = substr($url, 0, $pos);
        }

        if (count($parameters)) {
            return $url . '?' . http_build_query($parameters);
        }

        return $url;
    }
}
