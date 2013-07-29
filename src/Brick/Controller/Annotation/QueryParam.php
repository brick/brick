<?php

namespace Brick\Controller\Annotation;

use Brick\Http\Request;

/**
 * @Annotation
 */
class QueryParam extends RequestParam
{
    /**
     * {@inheritdoc}
     */
    public function getParameterType()
    {
        return 'query';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters(Request $request)
    {
        return $request->getQuery();
    }
}
