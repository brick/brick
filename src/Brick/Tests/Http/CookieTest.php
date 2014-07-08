<?php

namespace Brick\Tests\Http;

use Brick\Http\Cookie;

/**
 * Unit tests for class Cookie.
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorAndDefaults()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame('foo', $cookie->getName());
        $this->assertSame('bar', $cookie->getValue());

        $this->assertSame(0, $cookie->getExpires());
        $this->assertSame(null, $cookie->getPath());
        $this->assertSame(null, $cookie->getDomain());
        $this->assertFalse($cookie->isSecure());
        $this->assertFalse($cookie->isHttpOnly());
    }

    /**
     * @dataProvider providerParse
     *
     * @param string      $cookieString
     * @param string      $name
     * @param string      $value
     * @param integer     $expires
     * @param string|null $path
     * @param string|null $domain
     * @param boolean     $secure
     * @param boolean     $httpOnly
     */
    public function testParse($cookieString, $name, $value, $expires, $path, $domain, $secure, $httpOnly)
    {
        $cookie = Cookie::parse($cookieString);

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertSame($name, $cookie->getName());
        $this->assertSame($value, $cookie->getValue());
        $this->assertSame($expires, $cookie->getExpires());
        $this->assertSame($path, $cookie->getPath());
        $this->assertSame($domain, $cookie->getDomain());
        $this->assertSame($secure, $cookie->isSecure());
        $this->assertSame($httpOnly, $cookie->isHttpOnly());

        $cookie = Cookie::parse(strtoupper($cookieString));

        $name = strtoupper($name);
        $value = strtoupper($value);

        if ($path !== null) {
            $path = strtoupper($path);
        }

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertSame($name, $cookie->getName());
        $this->assertSame($value, $cookie->getValue());
        $this->assertSame($expires, $cookie->getExpires());
        $this->assertSame($path, $cookie->getPath());
        $this->assertSame($domain, $cookie->getDomain());
        $this->assertSame($secure, $cookie->isSecure());
        $this->assertSame($httpOnly, $cookie->isHttpOnly());
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['foo=bar', 'foo', 'bar', 0, null, null, false, false],
            ['foo=bar; unknown=parameter', 'foo', 'bar', 0, null, null, false, false],
            ['foo=bar; expires=Sun, 09 Sep 2001 01:46:40 GMT', 'foo', 'bar', 1000000000, null, null, false, false],
            ['foo=bar; path=/baz', 'foo', 'bar', 0, '/baz', null, false, false],
            ['foo=bar; domain=example.com', 'foo', 'bar', 0, null, 'example.com', false, false],
            ['foo=bar; secure', 'foo', 'bar', 0, null, null, true, false],
            ['foo=bar; httponly', 'foo', 'bar', 0, null, null, false, true],
        ];
    }

    /**
     * @dataProvider providerParseInvalidCookieThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param string $cookieString
     */
    public function testParseInvalidCookieThrowsException($cookieString)
    {
        Cookie::parse($cookieString);
    }

    /**
     * @return array
     */
    public function providerParseInvalidCookieThrowsException()
    {
        return [
            [''],
            ['='],
            ['foo='],
            ['=bar']
        ];
    }

    public function testGetSetExpires()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame($cookie, $cookie->setExpires(123456789));
        $this->assertSame(123456789, $cookie->getExpires());
    }

    public function testGetSetPath()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame($cookie, $cookie->setPath('/'));
        $this->assertSame('/', $cookie->getPath());

        $cookie->setPath(null);
        $this->assertNull($cookie->getPath());
    }

    public function testGetSetDomain()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame($cookie, $cookie->setDomain('example.com'));
        $this->assertSame('example.com', $cookie->getDomain());

        $cookie->setDomain(null);
        $this->assertNull($cookie->getDomain());
    }

    public function testIsSetSecure()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame($cookie, $cookie->setSecure(true));
        $this->assertTrue($cookie->isSecure());
    }

    public function testIsSetHttpOnly()
    {
        $cookie = new Cookie('foo', 'bar');

        $this->assertSame($cookie, $cookie->setHttpOnly(true));
        $this->assertTrue($cookie->isHttpOnly());
    }

    /**
     * @dataProvider providerIsExpiredIsPersistent
     *
     * @param integer $expires      The cookie expiration time.
     * @param boolean $isExpired    The expected value for isExpired.
     * @param boolean $isPersistent The expected value for isPersistent.
     */
    public function testIsExpiredIsPersistent($expires, $isExpired, $isPersistent)
    {
        $cookie = new Cookie('foo', 'bar');
        $cookie->setExpires($expires);

        $this->assertSame($isExpired, $cookie->isExpired());
        $this->assertSame($isPersistent, $cookie->isPersistent());
    }

    /**
     * @return array
     */
    public function providerIsExpiredIsPersistent()
    {
        return [
            [0,             false, false],
            [1,             true,  true],
            [time() - 1,    true,  true],
            [time() + 3600, false, true],
            [PHP_INT_MAX,   false, true]
        ];
    }

    public function testToString()
    {
        $cookie = new Cookie('foo', 'bar');
        $this->assertSame('foo=bar', (string) $cookie);

        $cookie->setExpires(2000000000);
        $this->assertSame('foo=bar; Expires=Wed, 18 May 2033 03:33:20 +0000', (string) $cookie);

        $cookie->setExpires(0)->setDomain('example.com');
        $this->assertSame('foo=bar; Domain=example.com', (string) $cookie);

        $cookie->setDomain(null)->setPath('/');
        $this->assertSame('foo=bar; Path=/', (string) $cookie);

        $cookie->setPath(null)->setSecure(true);
        $this->assertSame('foo=bar; Secure', (string) $cookie);

        $cookie->setHttpOnly(true);
        $this->assertSame('foo=bar; Secure; HttpOnly', (string) $cookie);
    }
}
