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

        $container->bind(SomeService::class)->with([
            'lifetime' => 'someservice.lifetime'
        ]);

        $container->bind(AnotherService::class);

        $service = $container->get(AnotherService::class);

        $this->assertInstanceOf(AnotherService::class, $service);
        $this->assertInstanceOf(DatabaseConnection::class, $service->connection);
        $this->assertInstanceOf(SomeService::class, $service->service);

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

        $containerWithoutAnnotations->bind(DatabaseConnection::class)->with([
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
        $container->bind(DatabaseConnection::class)->in($dbScope)->with([
                'hostname' => 'foo',
                'username' => 'foo',
                'password' => 'foo'
            ]);

        $container->alias('database.connection.shared', DatabaseConnection::class)->in($aliasScope);

        $this->assertResult($container, DatabaseConnection::class, $dbSame);
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

        $this->assertInstanceOf(DatabaseConnection::class, $a);
        $this->assertInstanceOf(DatabaseConnection::class, $b);

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
