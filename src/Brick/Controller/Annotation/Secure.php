<?php

namespace Brick\Controller\Annotation;

/**
 * This annotation forces a controller to be accessed on an HTTPS connection.
 *
 * @Annotation
 * @Target("METHOD")
 */
class Secure extends AbstractAnnotation
{
}
