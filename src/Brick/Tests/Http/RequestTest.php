<?php

namespace Brick\Tests\Http;

use Brick\Http\Request;

/**
 * Unit tests for class Request.
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $request = new Request();

        $this->assertSame('HTTP/1.0', $request->getProtocolVersion());
        $this->assertNull($request->getBody());
        $this->assertSame([], $request->getHeaders());
        $this->assertSame([], $request->getQuery());
        $this->assertSame([], $request->getPost());
        $this->assertSame([], $request->getCookie());
        $this->assertSame([], $request->getFiles());
        $this->assertFalse($request->isSecure());
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('localhost', $request->getHost());
        $this->assertSame(80, $request->getPort());
        $this->assertSame('/', $request->getRequestUri());
        $this->assertSame('/', $request->getPath());
        $this->assertSame('', $request->getQueryString());
        $this->assertSame('0.0.0.0', $request->getClientIp());
    }

    public function testGetCurrentWithNoServerVariablesUsesDefaults()
    {
        $_SERVER = [];

        $this->assertEquals(new Request(), Request::getCurrent());
    }

    /**
     * @dataProvider providerGetCurrentWithHttps
     *
     * @param string  $https    The HTTPS server value.
     * @param boolean $isSecure The expected isSecure() value.
     */
    public function testGetCurrentWithHttps($https, $isSecure)
    {
        $_SERVER = ['HTTPS' => $https];
        $request = Request::getCurrent();

        $this->assertSame($isSecure, $request->isSecure());
    }

    /**
     * @return array
     */
    public function providerGetCurrentWithHttps()
    {
        return [
            ['on', true],
            ['1', true],
            ['off', false],
            ['0', false],
            ['', false],
            ['anything else', false]
        ];
    }

    /**
     * @dataProvider providerGetCurrentWithHostPort
     *
     * @param array   $server         The contents of the $_SERVER array.
     * @param boolean $hostPortSource The value that will be passed to getCurrent().
     * @param string  $expectedHost   The expected host name.
     * @param integer $expectedPort   The expected port number.
     */
    public function testGetCurrentWithHostPort(array $server, $hostPortSource, $expectedHost, $expectedPort)
    {
        $_SERVER = $server;
        $request = Request::getCurrent(false, $hostPortSource);

        $this->assertSame($expectedHost, $request->getHost());
        $this->assertSame($expectedPort, $request->getPort());
    }

    /**
     * @return array
     */
    public function providerGetCurrentWithHostPort()
    {
        $https = [
            'HTTPS' => 'on'
        ];

        $httpHost = [
            'HTTP_HOST' => 'foo'
        ];

        $httpHostPort = [
            'HTTP_HOST' => 'foo:81'
        ];

        $serverName = [
            'SERVER_NAME' => 'bar'
        ];

        $serverPort = [
            'SERVER_PORT' => '82'
        ];

        return [
            [[],                                                 Request::PREFER_HTTP_HOST,   'localhost', 80],
            [[],                                                 Request::PREFER_SERVER_NAME, 'localhost', 80],
            [[],                                                 Request::ONLY_HTTP_HOST,     'localhost', 80],
            [[],                                                 Request::ONLY_SERVER_NAME,   'localhost', 80],

            [$https,                                             Request::PREFER_HTTP_HOST,   'localhost', 443],
            [$https,                                             Request::PREFER_SERVER_NAME, 'localhost', 443],
            [$https,                                             Request::ONLY_HTTP_HOST,     'localhost', 443],
            [$https,                                             Request::ONLY_SERVER_NAME,   'localhost', 443],

            [$httpHost,                                          Request::PREFER_HTTP_HOST,   'foo',       80],
            [$httpHost,                                          Request::PREFER_SERVER_NAME, 'foo',       80],
            [$httpHost,                                          Request::ONLY_HTTP_HOST,     'foo',       80],
            [$httpHost,                                          Request::ONLY_SERVER_NAME,   'localhost', 80],

            [$httpHost + $https,                                 Request::PREFER_HTTP_HOST,   'foo',       443],
            [$httpHost + $https,                                 Request::PREFER_SERVER_NAME, 'foo',       443],
            [$httpHost + $https,                                 Request::ONLY_HTTP_HOST,     'foo',       443],
            [$httpHost + $https,                                 Request::ONLY_SERVER_NAME,   'localhost', 443],

            [$httpHostPort,                                      Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort,                                      Request::PREFER_SERVER_NAME, 'foo',       81],
            [$httpHostPort,                                      Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort,                                      Request::ONLY_SERVER_NAME,   'localhost', 80],

            [$httpHostPort + $https,                             Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $https,                             Request::PREFER_SERVER_NAME, 'foo',       81],
            [$httpHostPort + $https,                             Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $https,                             Request::ONLY_SERVER_NAME,   'localhost', 443],

            [$serverName,                                        Request::PREFER_HTTP_HOST,   'bar',       80],
            [$serverName,                                        Request::PREFER_SERVER_NAME, 'bar',       80],
            [$serverName,                                        Request::ONLY_HTTP_HOST,     'localhost', 80],
            [$serverName,                                        Request::ONLY_SERVER_NAME,   'bar',       80],

            [$serverName + $https,                               Request::PREFER_HTTP_HOST,   'bar',       443],
            [$serverName + $https,                               Request::PREFER_SERVER_NAME, 'bar',       443],
            [$serverName + $https,                               Request::ONLY_HTTP_HOST,     'localhost', 443],
            [$serverName + $https,                               Request::ONLY_SERVER_NAME,   'bar',       443],

            [$serverPort,                                        Request::PREFER_HTTP_HOST,   'localhost', 82],
            [$serverPort,                                        Request::PREFER_SERVER_NAME, 'localhost', 82],
            [$serverPort,                                        Request::ONLY_HTTP_HOST,     'localhost', 80],
            [$serverPort,                                        Request::ONLY_SERVER_NAME,   'localhost', 82],

            [$serverPort + $https,                               Request::PREFER_HTTP_HOST,   'localhost', 82],
            [$serverPort + $https,                               Request::PREFER_SERVER_NAME, 'localhost', 82],
            [$serverPort + $https,                               Request::ONLY_HTTP_HOST,     'localhost', 443],
            [$serverPort + $https,                               Request::ONLY_SERVER_NAME,   'localhost', 82],

            [$serverName + $serverPort,                          Request::PREFER_HTTP_HOST,   'bar',       82],
            [$serverName + $serverPort,                          Request::PREFER_SERVER_NAME, 'bar',       82],
            [$serverName + $serverPort,                          Request::ONLY_HTTP_HOST,     'localhost', 80],
            [$serverName + $serverPort,                          Request::ONLY_SERVER_NAME,   'bar',       82],

            [$serverName + $serverPort + $https,                 Request::PREFER_HTTP_HOST,   'bar',       82],
            [$serverName + $serverPort + $https,                 Request::PREFER_SERVER_NAME, 'bar',       82],
            [$serverName + $serverPort + $https,                 Request::ONLY_HTTP_HOST,     'localhost', 443],
            [$serverName + $serverPort + $https,                 Request::ONLY_SERVER_NAME,   'bar',       82],

            [$httpHost + $serverName,                            Request::PREFER_HTTP_HOST,   'foo',       80],
            [$httpHost + $serverName,                            Request::PREFER_SERVER_NAME, 'bar',       80],
            [$httpHost + $serverName,                            Request::ONLY_HTTP_HOST,     'foo',       80],
            [$httpHost + $serverName,                            Request::ONLY_SERVER_NAME,   'bar',       80],

            [$httpHost + $serverName + $https,                   Request::PREFER_HTTP_HOST,   'foo',       443],
            [$httpHost + $serverName + $https,                   Request::PREFER_SERVER_NAME, 'bar',       443],
            [$httpHost + $serverName + $https,                   Request::ONLY_HTTP_HOST,     'foo',       443],
            [$httpHost + $serverName + $https,                   Request::ONLY_SERVER_NAME,   'bar',       443],

            [$httpHost + $serverPort,                            Request::PREFER_HTTP_HOST,   'foo',       80],
            [$httpHost + $serverPort,                            Request::PREFER_SERVER_NAME, 'foo',       82],
            [$httpHost + $serverPort,                            Request::ONLY_HTTP_HOST,     'foo',       80],
            [$httpHost + $serverPort,                            Request::ONLY_SERVER_NAME,   'localhost', 82],

            [$httpHost + $serverPort + $https,                   Request::PREFER_HTTP_HOST,   'foo',       443],
            [$httpHost + $serverPort + $https,                   Request::PREFER_SERVER_NAME, 'foo',       82],
            [$httpHost + $serverPort + $https,                   Request::ONLY_HTTP_HOST,     'foo',       443],
            [$httpHost + $serverPort + $https,                   Request::ONLY_SERVER_NAME,   'localhost', 82],

            [$httpHost + $serverName + $serverPort,              Request::PREFER_HTTP_HOST,   'foo',       80],
            [$httpHost + $serverName + $serverPort,              Request::PREFER_SERVER_NAME, 'bar',       82],
            [$httpHost + $serverName + $serverPort,              Request::ONLY_HTTP_HOST,     'foo',       80],
            [$httpHost + $serverName + $serverPort,              Request::ONLY_SERVER_NAME,   'bar',       82],

            [$httpHost + $serverName + $serverPort + $https,     Request::PREFER_HTTP_HOST,   'foo',       443],
            [$httpHost + $serverName + $serverPort + $https,     Request::PREFER_SERVER_NAME, 'bar',       82],
            [$httpHost + $serverName + $serverPort + $https,     Request::ONLY_HTTP_HOST,     'foo',       443],
            [$httpHost + $serverName + $serverPort + $https,     Request::ONLY_SERVER_NAME,   'bar',       82],

            [$httpHostPort + $serverName,                        Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverName,                        Request::PREFER_SERVER_NAME, 'bar',       81],
            [$httpHostPort + $serverName,                        Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverName,                        Request::ONLY_SERVER_NAME,   'bar',       80],

            [$httpHostPort + $serverName + $https,               Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverName + $https,               Request::PREFER_SERVER_NAME, 'bar',       81],
            [$httpHostPort + $serverName + $https,               Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverName + $https,               Request::ONLY_SERVER_NAME,   'bar',       443],

            [$httpHostPort + $serverPort,                        Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverPort,                        Request::PREFER_SERVER_NAME, 'foo',       82],
            [$httpHostPort + $serverPort,                        Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverPort,                        Request::ONLY_SERVER_NAME,   'localhost',  82],

            [$httpHostPort + $serverPort + $https,               Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverPort + $https,               Request::PREFER_SERVER_NAME, 'foo',       82],
            [$httpHostPort + $serverPort + $https,               Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverPort + $https,               Request::ONLY_SERVER_NAME,   'localhost',  82],

            [$httpHostPort + $serverName + $serverPort,          Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverName + $serverPort,          Request::PREFER_SERVER_NAME, 'bar',       82],
            [$httpHostPort + $serverName + $serverPort,          Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverName + $serverPort,          Request::ONLY_SERVER_NAME,   'bar',       82],

            [$httpHostPort + $serverName + $serverPort + $https, Request::PREFER_HTTP_HOST,   'foo',       81],
            [$httpHostPort + $serverName + $serverPort + $https, Request::PREFER_SERVER_NAME, 'bar',       82],
            [$httpHostPort + $serverName + $serverPort + $https, Request::ONLY_HTTP_HOST,     'foo',       81],
            [$httpHostPort + $serverName + $serverPort + $https, Request::ONLY_SERVER_NAME,   'bar',       82],
        ];
    }

    public function testGetCurrentWithRequestMethod()
    {
        $_SERVER = ['REQUEST_METHOD' => 'POST'];
        $request = Request::getCurrent();

        $this->assertSame('POST', $request->getMethod());
    }

    /**
     * @dataProvider providerGetCurrentWithRequestUri
     *
     * @param string $requestUri  The REQUEST_URI server value.
     * @param string $path        The expected path.
     * @param string $queryString The expected query string.
     * @param array  $query       The expected query array.
     */
    public function testGetCurrentWithRequestUri($requestUri, $path, $queryString, array $query)
    {
        $_SERVER = ['REQUEST_URI' => $requestUri];
        $request = Request::getCurrent();

        $this->assertSame($requestUri, $request->getRequestUri());
        $this->assertSame($path, $request->getPath());
        $this->assertSame($queryString, $request->getQueryString());
        $this->assertSame($query, $request->getQuery());
    }

    /**
     * @return array
     */
    public function providerGetCurrentWithRequestUri()
    {
        return [
            ['/foo',             '/foo',     '',        []],
            ['/foo/bar?',        '/foo/bar', '',        []],
            ['/foo/bar?a=1',     '/foo/bar', 'a=1',     ['a' => '1']],
            ['/foo/bar?a=1&a=2', '/foo/bar', 'a=1&a=2', ['a' => '2']],
            ['/foo/bar?a=1&b=2', '/foo/bar', 'a=1&b=2', ['a' => '1', 'b' => '2']],
            ['/foo/bar?a[]=1',   '/foo/bar', 'a[]=1',   ['a' => ['1']]]
        ];
    }

    public function testGetCurrentWithServerProtocol()
    {
        $_SERVER = ['SERVER_PROTOCOL' => 'HTTP/1.1'];
        $request = Request::getCurrent();

        $this->assertSame('HTTP/1.1', $request->getProtocolVersion());
    }

    public function testGetCurrentWithRemoteAddr()
    {
        $_SERVER = ['REMOTE_ADDR' => '12.34.56.78'];
        $request = Request::getCurrent();

        $this->assertSame('12.34.56.78', $request->getClientIp());
    }

    public function testGetCurrentWithHeaders()
    {
        $_SERVER = [
            'CONTENT_TYPE'    => 'text/xml',
            'CONTENT_LENGTH'  => '1234',
            'HTTP_USER_AGENT' => 'Mozilla'
        ];

        $request = Request::getCurrent();

        $this->assertEquals([
            'Content-Type'   => ['text/xml'],
            'Content-Length' => ['1234'],
            'User-Agent'     => ['Mozilla']
        ], $request->getHeaders());
    }

    /**
     * @dataProvider providerGetCurrentWithBody
     *
     * @param array   $server
     * @param boolean $hasBody
     */
    public function testGetCurrentWithBody(array $server, $hasBody)
    {
        $_SERVER = $server;
        $request = Request::getCurrent();

        $this->assertSame($hasBody, $request->getBody() !== null);
    }

    /**
     * @return array
     */
    public function providerGetCurrentWithBody()
    {
        return [
            [[],                                      false],
            [['CONTENT_TYPE' => 'text/plain'],        false],
            [['CONTENT_LENGTH' => '123'],             true],
            [['HTTP_TRANSFER_ENCODING' => 'chunked'], true]
        ];
    }

    public function testGetCurrentWithCookie()
    {
        $_COOKIE = ['foo' => 'bar'];
        $request = Request::getCurrent();

        $this->assertSame($_COOKIE, $request->getCookie());
    }

    public function testGetCurrentWithPost()
    {
        $_POST = ['foo' => 'bar'];
        $request = Request::getCurrent();

        $this->assertSame($_POST, $request->getPost());
    }

    public function testGetCurrentTrustProxyOff()
    {
        $_SERVER = $this->getProxyServerVariables();
        $request = Request::getCurrent();

        $this->assertSame('1.2.3.4', $request->getClientIp());
        $this->assertSame('foo', $request->getHost());
        $this->assertSame(81, $request->getPort());
        $this->assertFalse($request->isSecure());
    }

    public function testGetCurrentTrustProxyOn()
    {
        $_SERVER = $this->getProxyServerVariables();
        $request = Request::getCurrent(true);

        $this->assertSame('5.6.7.8', $request->getClientIp());
        $this->assertSame('bar', $request->getHost());
        $this->assertSame(444, $request->getPort());
        $this->assertTrue($request->isSecure());
    }

    /**
     * @return array
     */
    private function getProxyServerVariables()
    {
        return [
            'REMOTE_ADDR' => '1.2.3.4',
            'HTTP_HOST'   => 'foo:81',
            'HTTPS'       => 'off',

            'HTTP_X_FORWARDED_FOR'   => '5.6.7.8',
            'HTTP_X_FORWARDED_HOST'  => 'bar',
            'HTTP_X_FORWARDED_PORT'  => '444',
            'HTTP_X_FORWARDED_PROTO' => 'https'
        ];
    }

    /**
     * @dataProvider providerGetCurrentWithFiles
     *
     * @param string  $key    The array key.
     * @param string  $path   The expected path.
     * @param string  $name   The expected file name.
     * @param string  $type   The expected MIME type.
     * @param integer $size   The expected file size.
     * @param integer $status The expected upload status.
     */
    public function testGetCurrentWithFiles($key, $path, $name, $type, $size, $status)
    {
        $_FILES = $this->getSampleFilesArray();
        $request = Request::getCurrent();
        $file = $request->getFile($key);

        $this->assertSame($name, $file->getName());
        $this->assertSame($type, $file->getType());
        $this->assertSame($size, $file->getSize());
        $this->assertSame($path, $file->getPath());
        $this->assertSame($status, $file->getStatus());
    }

    /**
     * @return array
     */
    public function providerGetCurrentWithFiles()
    {
        return [
            ['logo',                       '/tmp/001', 'logo.png',  'image/png',  1001, UPLOAD_ERR_OK],
            ['pictures[0]',                '/tmp/002', 'a.jpg',     'image/jpeg', 1002, UPLOAD_ERR_EXTENSION],
            ['pictures[1]',                '/tmp/003', 'b.jpx',     'image/jpx',  1003, UPLOAD_ERR_CANT_WRITE],
            ['files[images][logo][small]', '/tmp/004', 'small.bmp', 'image/bmp',  1004, UPLOAD_ERR_FORM_SIZE],
            ['files[images][logo][large]', '/tmp/005', 'large.gif', 'image/gif',  1005, UPLOAD_ERR_PARTIAL]
        ];
    }

    public function testGetSetQuery()
    {
        $query = [
            'a' => 'x',
            'b' => [
                'c' => [
                    'd' => 'y',
                ]
            ]
        ];

        $request = new Request();
        $request->setRequestUri('/test?foo=bar');

        $this->assertSame($request, $request->setQuery($query));

        $this->assertSame($query, $request->getQuery());
        $this->assertSame('a=x&b%5Bc%5D%5Bd%5D=y', $request->getQueryString());
        $this->assertSame('/test?a=x&b%5Bc%5D%5Bd%5D=y', $request->getRequestUri());

        $this->assertNull($request->getQuery('foo'));
        $this->assertSame('x', $request->getQuery('a'));
        $this->assertSame('y', $request->getQuery('b.c.d'));
        $this->assertSame('y', $request->getQuery('b[c][d]'));
        $this->assertSame(['d' => 'y'], $request->getQuery('b.c'));
        $this->assertSame(['d' => 'y'], $request->getQuery('b[c]'));
        $this->assertNull($request->getQuery('b.c.d.e'));
        $this->assertNull($request->getQuery('b[c][d][e]'));
    }

    public function testGetSetPost()
    {
        $post = [
            'a' => 'x',
            'b' => [
                'c' => 'y'
            ]
        ];

        $request = new Request();

        $this->assertSame($request, $request->setPost($post));

        $this->assertSame($post, $request->getPost());
        $this->assertSame('x-www-form-urlencoded', $request->getHeader('Content-Type'));
        $this->assertSame('14', $request->getHeader('Content-Length'));
        $this->assertSame('a=x&b%5Bc%5D=y', (string) $request->getBody());

        $this->assertNull($request->getPost('foo'));
        $this->assertSame('x', $request->getPost('a'));
        $this->assertSame('y', $request->getPost('b.c'));
        $this->assertSame('y', $request->getPost('b[c]'));
        $this->assertSame(['c' => 'y'], $request->getPost('b'));
        $this->assertNull($request->getPost('b.c.d'));
        $this->assertNull($request->getPost('b[c][d]'));
    }

    public function testGetSetAddCookies()
    {
        $cookies = [
            'a' => 'w',
            'b' => 'y'
        ];

        $request = new Request();

        $this->assertSame($request, $request->setCookies($cookies));
        $this->assertSame($cookies, $request->getCookie());

        $this->assertSame($request, $request->addCookies([
            'a' => 'x',
            'c' => 'z'
        ]));

        $this->assertSame('x', $request->getCookie('a'));
        $this->assertSame('y', $request->getCookie('b'));
        $this->assertSame('z', $request->getCookie('c'));
    }

    /**
     * @dataProvider providerGetFileReturnsNullWhenNotAFile
     *
     * @param string $name
     */
    public function testGetFileReturnsNullWhenNotAFile($name)
    {
        $_FILES = $this->getSampleFilesArray();
        $request = Request::getCurrent();

        $this->assertNull($request->getFile($name));
    }

    /**
     * @return array
     */
    public function providerGetFileReturnsNullWhenNotAFile()
    {
        return [
            ['foo'],
            ['pictures'],
            ['pictures.0.test'],
            ['pictures[0][test]'],
            ['files'],
            ['files.images'],
            ['files.images.logo'],
            ['files.images.logo.small.test'],
            ['files[images]'],
            ['files[images[logo]'],
            ['files[images][logo][small][test]'],
        ];
    }

    /**
     * @dataProvider providerGetFile
     *
     * @param string $key
     * @param string $expectedFileName
     */
    public function testGetFile($key, $expectedFileName)
    {
        $_FILES = $this->getSampleFilesArray();
        $request = Request::getCurrent();

        $this->assertSame($expectedFileName, $request->getFile($key)->getName());
    }

    /**
     * @return array
     */
    public function providerGetFile()
    {
        return [
            ['logo',                       'logo.png'],
            ['pictures.0',                 'a.jpg'],
            ['pictures[1]',                'b.jpx'],
            ['files.images.logo.small',    'small.bmp'],
            ['files[images][logo][large]', 'large.gif']
        ];
    }

    /**
     * @dataProvider providerGetFiles
     *
     * @param string $key
     * @param array  $expectedFileNames
     */
    public function testGetFiles($key, $expectedFileNames)
    {
        $_FILES = $this->getSampleFilesArray();
        $request = Request::getCurrent();

        $files = $request->getFiles($key);

        foreach ($files as & $file) {
            $file = $file->getName();
        }

        $this->assertSame($expectedFileNames, $files);
    }

    /**
     * @return array
     */
    public function providerGetFiles()
    {
        return [
            ['foo',                       []],
            ['logo',                      ['logo.png']],
            ['pictures',                  [0 => 'a.jpg', 1 => 'b.jpx']],
            ['files',                     []],
            ['files.images',              []],
            ['files[images]',             []],
            ['files.images.logo',         ['small' => 'small.bmp', 'large' => 'large.gif']],
            ['files[images][logo]',       ['small' => 'small.bmp', 'large' => 'large.gif']],
            ['files.images.logo.test',    []],
            ['files[images][logo][test]', []],
        ];
    }

    public function testGetStartLine()
    {
        $request = (new Request())
            ->setMethod('POST')
            ->setRequestUri('/test')
            ->setProtocolVersion('HTTP/1.1');

        $this->assertSame('POST /test HTTP/1.1', $request->getStartLine());
    }

    /**
     * @dataProvider providerGetSetIsMethod
     *
     * @param string $setMethod The method to set.
     * @param string $getMethod The expected method to get.
     * @param array  $isMethod  An associative array of methods to test against.
     */
    public function testGetSetIsMethod($setMethod, $getMethod, array $isMethod)
    {
        $request = new Request();

        $this->assertSame($request, $request->setMethod($setMethod));
        $this->assertSame($getMethod, $request->getMethod());

        foreach ($isMethod as $method => $expectedIsMethod) {
            $this->assertSame($expectedIsMethod, $request->isMethod($method));
        }
    }

    /**
     * @return array
     */
    public function providerGetSetIsMethod()
    {
        return [
            ['GET',     'GET',     ['GET'     => true, 'get'     => true,  'Get'     => true,  'POST' => false]],
            ['Connect', 'CONNECT', ['Connect' => true, 'connect' => true,  'CONNECT' => true,  'GET'  => false]],
            ['Delete',  'DELETE',  ['Delete'  => true, 'delete'  => true,  'DELETE'  => true,  'GET'  => false]],
            ['Get',     'GET',     ['Get'     => true, 'get'     => true,  'GET'     => true,  'POST' => false]],
            ['Head',    'HEAD',    ['Head'    => true, 'head'    => true,  'HEAD'    => true,  'GET'  => false]],
            ['Options', 'OPTIONS', ['Options' => true, 'options' => true,  'OPTIONS' => true,  'GET'  => false]],
            ['Post',    'POST',    ['Post'    => true, 'post'    => true,  'POST'    => true,  'GET'  => false]],
            ['Put',     'PUT',     ['Put'     => true, 'put'     => true,  'PUT'     => true,  'GET'  => false]],
            ['Trace',   'TRACE',   ['Trace'   => true, 'trace'   => true,  'TRACE'   => true,  'GET'  => false]],
            ['Track',   'TRACK',   ['Track'   => true, 'track'   => true,  'TRACK'   => true,  'GET'  => false]],
            ['Other',   'Other',   ['Other'   => true, 'other'   => false, 'OTHER'   => false, 'GET'  => false]]
        ];
    }

    /**
     * @dataProvider providerIsMethodSafe
     *
     * @param string  $method
     * @param boolean $isSafe
     */
    public function testIsMethodSafe($method, $isSafe)
    {
        $request = new Request();

        $request->setMethod($method);
        $this->assertSame($isSafe, $request->isMethodSafe());

        $request->setMethod(strtolower($method));
        $this->assertSame($isSafe, $request->isMethodSafe());
    }

    /**
     * @return array
     */
    public function providerIsMethodSafe()
    {
        return [
            ['GET', true],
            ['HEAD', true],
            ['POST', false],
            ['PUT', false],
            ['DELETE', false]
        ];
    }

    /**
     * @dataProvider providerGetSetScheme
     *
     * @param string  $setScheme The scheme to set.
     * @param string  $getScheme The expected scheme to get.
     * @param boolean $isSecure  The expected secure flag.
     */
    public function testGetSetScheme($setScheme, $getScheme, $isSecure)
    {
        $request = new Request();

        $this->assertSame($request, $request->setScheme($setScheme));
        $this->assertSame($getScheme, $request->getScheme());
        $this->assertSame($isSecure, $request->isSecure());
    }

    /**
     * @return array
     */
    public function providerGetSetScheme()
    {
        return [
            ['http',  'http',  false],
            ['Http',  'http',  false],
            ['HTTP',  'http',  false],
            ['https', 'https', true],
            ['Https', 'https', true],
            ['HTTPS', 'https', true]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidSchemeThrowsException()
    {
        $request = new Request();
        $request->setScheme('ftp');
    }

    public function testGetSetHostPort()
    {
        $request = new Request();

        $this->assertSame($request, $request->setHost('example.com'));
        $this->assertSame('example.com', $request->getHost());
        $this->assertSame('example.com', $request->getHeader('Host'));

        $this->assertSame($request, $request->setPort('81'));
        $this->assertSame(81, $request->getPort());
        $this->assertSame('example.com:81', $request->getHeader('Host'));
    }

    public function testGetHostParts()
    {
        $request = new Request();
        $request->setHost('www.example.com');

        $this->assertSame(['www', 'example', 'com'], $request->getHostParts());
    }

    public function testGetSetPath()
    {
        $request = new Request();
        $request->setRequestUri('/user/profile?user=123');

        $this->assertSame('/user/profile', $request->getPath());
        $this->assertSame($request, $request->setPath('/user/edit'));
        $this->assertSame('/user/edit', $request->getPath());
        $this->assertSame('/user/edit?user=123', $request->getRequestUri());
    }

    /**
     * @dataProvider providerGetPathParts
     *
     * @param string $path
     * @param array  $expectedParts
     */
    public function testGetPathParts($path, array $expectedParts)
    {
        $request = new Request();
        $request->setPath($path);

        $this->assertSame($expectedParts, $request->getPathParts());
    }

    /**
     * @return array
     */
    public function providerGetPathParts()
    {
        return [
            ['/',            []],
            ['//',           []],
            ['/foo',         ['foo']],
            ['//foo',        ['foo']],
            ['/foo/',        ['foo']],
            ['//foo//',      ['foo']],
            ['/foo/bar',     ['foo', 'bar']],
            ['//foo//bar//', ['foo', 'bar']]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetPathThrowsExceptionWhenPathIsInvalid()
    {
        $request = new Request();
        $request->setPath('/user/edit?user=123');
    }

    public function testGetSetQueryString()
    {
        $request = new Request();
        $request->setRequestUri('/user/profile?user=123');

        $this->assertSame('user=123', $request->getQueryString());
        $this->assertSame($request, $request->setQueryString('user=456'));
        $this->assertSame('user=456', $request->getQueryString());
        $this->assertSame('/user/profile?user=456', $request->getRequestUri());
    }

    public function testGetSetRequestUri()
    {
        $request = new Request();

        $this->assertSame($request, $request->setRequestUri('/test?foo=bar'));

        $this->assertSame('/test?foo=bar', $request->getRequestUri());
        $this->assertSame('/test', $request->getPath());
        $this->assertSame('foo=bar', $request->getQueryString());
        $this->assertSame(['foo' => 'bar'], $request->getQuery());
    }

    /**
     * @dataProvider providerGetSetUrl
     *
     * @param string       $url         The URL to test.
     * @param string|null  $expectedUrl The expected URL, or NULL to use the original URL.
     * @param string       $host        The expected host name.
     * @param integer      $port        The expected port number.
     * @param string       $requestUri  The expected request URI.
     * @param string       $path        The expected path.
     * @param string       $qs          The expected query string.
     * @param string       $isSecure    The expected isSecure flag.
     * @param array        $query       The expected query parameters.
     */
    public function testGetSetUrl($url, $expectedUrl, $host, $port, $requestUri, $path, $qs, $isSecure, array $query)
    {
        $request = new Request();

        // Set some values to ensure the defaults get overridden.
        $request->setPort(999);
        $request->setSecure(true);
        $request->setRequestUri('/path?a=b');

        $this->assertSame($request, $request->setUrl($url));
        $this->assertSame($expectedUrl ?: $url, $request->getUrl());
        $this->assertSame($host, $request->getHost());
        $this->assertSame($port, $request->getPort());
        $this->assertSame($requestUri, $request->getRequestUri());
        $this->assertSame($path, $request->getPath());
        $this->assertSame($qs, $request->getQueryString());
        $this->assertSame($isSecure, $request->isSecure());
        $this->assertSame($query, $request->getQuery());
    }

    /**
     * @return array
     */
    public function providerGetSetUrl()
    {
        return [
            ['http://foo',            'http://foo/',  'foo', 80,  '/',      '/',     '',    false, []],
            ['https://foo/',          'https://foo/', 'foo', 443, '/',      '/',     '',    true,  []],
            ['http://foo:81/x/y',     null,           'foo', 81,  '/x/y',   '/x/y',  '',    false, []],
            ['https://x.y:444/z?x=y', null,           'x.y', 444, '/z?x=y', '/z',    'x=y', true,  ['x' => 'y']]
        ];
    }

    /**
     * @dataProvider providerUrlBase
     *
     * @param string $url
     * @param string $expectedUrlBase
     */
    public function testGetUrlBase($url, $expectedUrlBase)
    {
        $request = new Request();
        $request->setUrl($url);

        $this->assertSame($expectedUrlBase, $request->getUrlBase());
    }

    /**
     * @return array
     */
    public function providerUrlBase()
    {
        return [
            ['http://example.com',              'http://example.com'],
            ['http://example.com/',             'http://example.com'],
            ['https://example.com',             'https://example.com'],
            ['https://example.com/',            'https://example.com'],
            ['http://example.com/foo/bar',      'http://example.com'],
            ['https://example.com/foo/bar?x=y', 'https://example.com']
        ];
    }

    public function testIsSetSecure()
    {
        $request = new Request();

        // Making the request secure should change the port number to 443.
        $this->assertSame($request, $request->setSecure(true));
        $this->assertTrue($request->isSecure());
        $this->assertSame(443, $request->getPort());

        // Reverting to non-secure should change the port number to 80.
        $request->setSecure(false);
        $this->assertFalse($request->isSecure());
        $this->assertSame(80, $request->getPort());

        // Making the request secure with a non-standard port should not change the port.
        $request->setPort(81)->setSecure(true);
        $this->assertTrue($request->isSecure());
        $this->assertSame(81, $request->getPort());
    }

    public function testGetSetClientIp()
    {
        $request = new Request();

        $this->assertSame($request, $request->setClientIp('4.3.2.1'));
        $this->assertSame('4.3.2.1', $request->getClientIp());
    }

    /**
     * @dataProvider providerAcceptLanguage
     *
     * @param string $acceptLanguage The Accept-Language header.
     * @param array  $expectedResult The expected result.
     */
    public function testGetAcceptLanguage($acceptLanguage, $expectedResult)
    {
        $request = new Request();

        $request->setHeader('Accept-Language', $acceptLanguage);
        $this->assertSame($expectedResult, $request->getAcceptLanguage());
    }

    /**
     * @return array
     */
    public function providerAcceptLanguage()
    {
        return [
            ['', []],
            [' ', []],
            ['en-us', ['en-us' => 1.0]],
            ['en-us, fr-fr', ['en-us' => 1.0, 'fr-fr' => 1.0]],
            ['en-us, fr-fr; q=0.5', ['en-us' => 1.0, 'fr-fr' => 0.5]],
            ['en-us; q=0.5, fr-fr', ['fr-fr' => 1.0, 'en-us' => 0.5]],
            ['en-us, fr-fr; q=0.5, en-gb', ['en-us' => 1.0, 'en-gb' => 1.0, 'fr-fr' => 0.5]],
            ['en-ca; q=0.6, en; q=0.5, fr; q=0, en-us, en-gb; q=0.9', ['en-us' => 1.0, 'en-gb' => 0.9, 'en-ca' => 0.6, 'en' => 0.5, 'fr' => 0.0]],
            [' en-ca, fr-fr ; q=1.1, fr-ca ; q=0.9 , en-us  ,  pt-br ; q=0.000, en-gb ; q=1.000, fr-be ; q=1.0000', ['en-ca' => 1.0, 'en-us' => 1.0, 'en-gb' => 1.0, 'fr-ca' => 0.9, 'pt-br' => 0.0]]
        ];
    }

    /**
     * @return array
     */
    private function getSampleFilesArray()
    {
        return [
            'logo' => [
                'tmp_name' => '/tmp/001',
                'name'     => 'logo.png',
                'type'     => 'image/png',
                'size'     => 1001,
                'error'    => UPLOAD_ERR_OK,
            ],
            'pictures' => [
                'tmp_name' => [
                    0 => '/tmp/002',
                    1 => '/tmp/003'
                ],
                'name' => [
                    0 => 'a.jpg',
                    1 => 'b.jpx'
                ],
                'type' => [
                    0 => 'image/jpeg',
                    1 => 'image/jpx'
                ],
                'size' => [
                    0 => 1002,
                    1 => 1003
                ],
                'error' => [
                    0 => UPLOAD_ERR_EXTENSION,
                    1 => UPLOAD_ERR_CANT_WRITE
                ]
            ],
            'files' => [
                'tmp_name' => [
                    'images' => [
                        'logo' => [
                            'small' => '/tmp/004',
                            'large' => '/tmp/005'
                        ]
                    ]
                ],
                'name' => [
                    'images' => [
                        'logo' => [
                            'small' => 'small.bmp',
                            'large' => 'large.gif'
                        ]
                    ]
                ],
                'type' => [
                    'images' => [
                        'logo' => [
                            'small' => 'image/bmp',
                            'large' => 'image/gif'
                        ]
                    ]
                ],
                'size' => [
                    'images' => [
                        'logo' => [
                            'small' => 1004,
                            'large' => 1005
                        ]
                    ]
                ],
                'error' => [
                    'images' => [
                        'logo' => [
                            'small' => UPLOAD_ERR_FORM_SIZE,
                            'large' => UPLOAD_ERR_PARTIAL
                        ]
                    ]
                ],
            ]
        ];
    }
}
