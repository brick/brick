<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\UrlValidator;

/**
 * Unit tests for the URL validator.
 */
class UrlValidatorTest extends AbstractTestCase
{
    public function testUrlValidator()
    {
        $validator = new UrlValidator();

        $this->doTestValidator($validator, [
            ''                    => ['validator.url.invalid'],
            'http'                => ['validator.url.invalid'],
            'http:'               => ['validator.url.invalid'],
            'http:test.com'       => ['validator.url.invalid'],
            'http:test.com/test'  => ['validator.url.invalid'],
            'http:/'              => ['validator.url.invalid'],
            'http:/test.com'      => ['validator.url.invalid'],
            'http:/test.com/test' => ['validator.url.invalid'],

            'http://test.com'                        => [],
            'http://test.com/'                       => [],
            'https://test.com/test?a=b'              => [],
            'https://test.com/test?a=b&c=d'          => [],
            'https://test.com/test?a=b&c=d#fragment' => [],
            'ftp://user:pass@example.com'            => [],
            'ftp://user:pass@example.com/a/b'        => [],
        ]);
    }
}
