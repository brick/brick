<?php

namespace Brick\UrlBuilder;

use Brick\IdentityResolver\IdentityResolver;

/**
 * @todo should maybe be called UriBuilder (i, or l)
 */
class UrlBuilder
{
    /**
     * @var \Brick\IdentityResolver\IdentityResolver
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
                    $parameters[$key] = $this->resolver->getIdentity($value);
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
