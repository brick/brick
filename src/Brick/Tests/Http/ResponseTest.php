<?php

namespace Brick\Tests\Http;

use Brick\Http\Cookie;
use Brick\Http\MessageBody;
use Brick\Http\Response;

/**
 * Unit tests for class Request.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $response = new Response();

        $this->assertSame('HTTP/1.0 200 OK', $response->getStartLine());
        $this->assertSame('HTTP/1.0', $response->getProtocolVersion());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame([], $response->getHeaders());
        $this->assertSame([], $response->getCookies());
        $this->assertNull($response->getBody());
    }

    /**
     * @dataProvider providerGetSetStatusCode
     *
     * @param string      $statusCode           The status code to set.
     * @param string|null $reasonPhrase         The reason phrase to set, or null to skip.
     * @param string      $expectedReasonPhrase The expected reason phrase.
     */
    public function testGetSetStatusCode($statusCode, $reasonPhrase, $expectedReasonPhrase)
    {
        $response = new Response();

        $this->assertSame($response, $response->setStatusCode($statusCode, $reasonPhrase));
        $this->assertSame($statusCode, $response->getStatusCode());
        $this->assertSame($expectedReasonPhrase, $response->getReasonPhrase());
    }

    /**
     * @return array
     */
    public function providerGetSetStatusCode()
    {
        return [
            [404, null,     'Not Found'],
            [404, 'Custom', 'Custom'],
            [999, null,     'Unknown'],
            [999, 'Custom', 'Custom']
        ];
    }

    /**
     * @dataProvider providerSetInvalidStatusCodeThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param integer $statusCode
     */
    public function testSetInvalidStatusCodeThrowsException($statusCode)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
    }

    /**
     * @return array
     */
    public function providerSetInvalidStatusCodeThrowsException()
    {
        return [
            [0],
            [99],
            [1000]
        ];
    }

    public function testSetRemoveCookie()
    {
        $response = new Response();

        $foo = new Cookie('foo', 'bar');
        $foo->setSecure(true);

        $this->assertSame($response, $response->setCookie($foo));
        $this->assertSame([$foo], $response->getCookies());
        $this->assertSame(['foo=bar; Secure'], $response->getHeaderAsArray('Set-Cookie'));

        $bar = new Cookie('bar', 'baz');
        $bar->setHttpOnly(true);

        $this->assertSame($response, $response->setCookie($bar));
        $this->assertSame([$foo, $bar], $response->getCookies());
        $this->assertSame(['foo=bar; Secure', 'bar=baz; HttpOnly'], $response->getHeaderAsArray('Set-Cookie'));

        $this->assertSame($response, $response->removeCookies());
        $this->assertSame([], $response->getCookies());
        $this->assertSame([], $response->getHeaderAsArray('Set-Cookie'));
    }

    public function testSetContent()
    {
        $response = new Response();

        $this->assertSame($response, $response->setContent('Hello World'));
        $this->assertInstanceOf(MessageBody::class, $response->getBody());
        $this->assertSame('Hello World', (string) $response->getBody());
    }

    public function testIsType()
    {
        $response = new Response();

        for ($statusCode = 100; $statusCode <= 999; $statusCode++) {
            $response->setStatusCode($statusCode);
            $digit = substr($statusCode, 0, 1);

            $this->assertSame($digit == 1, $response->isInformational());
            $this->assertSame($digit == 2, $response->isSuccessful());
            $this->assertSame($digit == 3, $response->isRedirection());
            $this->assertSame($digit == 4, $response->isClientError());
            $this->assertSame($digit == 5, $response->isServerError());
        }
    }
}
