<?php

namespace Brick\Controller\Annotation;

use Brick\Http\Request;

/**
 * This annotation requires the RequestParamListener to be registered with the application.
 *
 * @Annotation
 * @Target("METHOD")
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
