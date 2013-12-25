<?php

namespace Brick\Tests;

use Brick\DependencyInjection\Scope;
use Doctrine\Common\Annotations\AnnotationReader;
use Brick\DependencyInjection\InjectionPolicy\AnnotationPolicy;
use Brick\DependencyInjection\Annotation\Inject;
use Brick\DependencyInjection\Container;

/**
 * Unit test for the dependency injection container.
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider containerProvider
     */
    public function testContainer(Container $container)
    {
        $container->add([
            'db.host' => 'localhost',
            'db.user' => 'root',
            'db.pass' => 'passw0rd'
        ]);

        $container->set('someservice.lifetime', 3600);

        $container->bind('Brick\Tests\SomeService')->toSelf()->withParameters([
            'lifetime' => 'someservice.lifetime'
        ]);

        $container->bind('Brick\Tests\AnotherService')->toSelf();

        $service = $container->get('Brick\Tests\AnotherService');

        $this->assertInstanceOf('Brick\Tests\AnotherService', $service);
        $this->assertInstanceOf('Brick\Tests\DatabaseConnection', $service->connection);
        $this->assertInstanceOf('Brick\Tests\SomeService', $service->service);

        $this->assertEquals('localhost', $service->connection->hostname);
        $this->assertEquals('root', $service->connection->username);
        $this->assertEquals('passw0rd', $service->connection->password);

        $this->assertEquals(3600, $service->service->lifetime);
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $containerWithoutAnnotations = Container::create();

        $containerWithoutAnnotations->bind('Brick\Tests\DatabaseConnection')->toSelf()->withParameters([
            'hostname' => 'db.host',
            'username' => 'db.user',
            'password' => 'db.pass'
        ]);

        $reader = new AnnotationReader();
        $policy = new AnnotationPolicy($reader);

        $containerWithAnnotations = new Container($policy);

        return [
            [$containerWithoutAnnotations],
            [$containerWithAnnotations]
        ];
    }

    /**
     * @dataProvider testScopeProvider
     */
    public function testScope(Scope $dbScope, Scope $aliasScope, $dbSame, $aliasSame)
    {
        $container = Container::create();

        $container->set('foo', 'bar');
        $container->bind('Brick\Tests\DatabaseConnection')
            ->toSelf()
            ->in($dbScope)
            ->withParameters([
                'hostname' => 'foo',
                'username' => 'foo',
                'password' => 'foo'
            ]);

        $container->bind('database.connection.shared')
            ->aliasOf('Brick\Tests\DatabaseConnection')
            ->in($aliasScope);

        $this->assertResult($container, 'Brick\Tests\DatabaseConnection', $dbSame);
        $this->assertResult($container, 'database.connection.shared', $aliasSame);
    }

    /**
     * @return array
     */
    public function testScopeProvider()
    {
        return [
            [Scope::singleton(), Scope::singleton(), true, true],
            [Scope::singleton(), Scope::prototype(), true, true],
            [Scope::prototype(), Scope::singleton(), false, true],
            [Scope::prototype(), Scope::prototype(), false, false],
        ];
    }

    /**
     * @param Container $container
     * @param string    $key
     * @param bool      $same
     */
    private function assertResult(Container $container, $key, $same)
    {
        $a = $container->get($key);
        $b = $container->get($key);

        $this->assertInstanceOf('Brick\Tests\DatabaseConnection', $a);
        $this->assertInstanceOf('Brick\Tests\DatabaseConnection', $b);

        $same ? $this->assertSame($a, $b) : $this->assertNotSame($a, $b);
    }
}

/**
 * @Inject
 */
class DatabaseConnection
{
    public $hostname;
    public $username;
    public $password;

    /**
     * @Inject(hostname="db.host", username="db.user", password="db.pass")
     */
    public function __construct($hostname, $username, $password)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
    }
}

class SomeService
{
    public $connection;
    public $lifetime;

    public function __construct(DatabaseConnection $connection, $lifetime)
    {
        $this->connection = $connection;
        $this->lifetime = $lifetime;
    }
}

class AnotherService
{
    public $connection;
    public $service;

    public function __construct(DatabaseConnection $connection, SomeService $service)
    {
        $this->connection = $connection;
        $this->service = $service;
    }
}