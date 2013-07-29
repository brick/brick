<?php

namespace Brick\Controller\Annotation;

use Brick\Http\Request;

/**
 * @Annotation
 */
class PostParam extends RequestParam
{
    /**
     * {@inheritdoc}
     */
    public function getParameterType()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters(Request $request)
    {
        return $request->getPost();
    }
}
