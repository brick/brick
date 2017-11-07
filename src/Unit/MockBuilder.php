<?php

namespace Brick\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Helper class to create simple stubs with PHPUnit.
 */
class MockBuilder
{
    /**
     * @var TestCase
     */
    protected $testCase;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mock;

    /**
     * Class constructor
     *
     * @param TestCase $testCase
     * @param string   $className
     */
    public function __construct(TestCase $testCase, string $className)
    {
        $this->testCase = $testCase;
        $this->mock = $testCase->getMockBuilder($className)
                               ->disableOriginalConstructor()
                               ->getMock();
    }

    /**
     * Add a method to the stub
     *
     * @param string $methodName
     * @param mixed  $returnValue
     *
     * @return void
     */
    public function addMethod(string $methodName, $returnValue) : void
    {
        $this->mock->expects($this->testCase->any())
                   ->method($methodName)
                   ->will($this->testCase->returnValue($returnValue));
    }

    /**
     * @param string $methodName
     * @param bool $moreThanOnce
     *
     * @return void
     */
    public function addRequiredMethod(string $methodName, bool $moreThanOnce = false) : void
    {
        if ($moreThanOnce) {
            $this->mock->expects($this->testCase->atLeastOnce())
                ->method($methodName)
                ->with();

        } else {
            $this->mock->expects($this->testCase->once())
                ->method($methodName)
                ->with();
        }
    }

    /**
     * Returns the stub created
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getMock() : \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->mock;
    }
}
