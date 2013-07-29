<?php

namespace Brick\Unit;

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
     * @param \PHPUnit_Framework_TestCase $testCase
     * @param string   $className
     */
    public function __construct(\PHPUnit_Framework_TestCase $testCase, $className)
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
     */
    public function addMethod($methodName, $returnValue)
    {
        $this->mock->expects($this->testCase->any())
                   ->method($methodName)
                   ->will($this->testCase->returnValue($returnValue));
    }

    /**
     * @param string $methodName
     * @param bool $moreThanOnce
     */
    public function addRequiredMethod($methodName, $moreThanOnce = false)
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
    public function getMock()
    {
        return $this->mock;
    }
}
