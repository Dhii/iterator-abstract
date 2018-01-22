<?php

namespace Dhii\Iterator\Test;

use Dhii\Iterator\IterationInterface;
use Dhii\Iterator\IterationAwareTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class IterationAwareTraitTest extends TestCase
{
    /**
     * The classname of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Iterator\\IterationAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockForTrait(
            static::TEST_SUBJECT_CLASSNAME,
            [],
            '',
            true,
            true,
            true,
            /* methods */
            []
        );

        return $mock;
    }

    /**
     * Creates a new iteration instance.
     *
     * @since [*next-version*]
     *
     * @return IterationInterface
     */
    public function createIteration()
    {
        $mock = $this->mock('Dhii\\Iterator\\IterationInterface')
                     ->getValue()
                     ->getKey();

        return $mock->new();
    }

    /**
     * Tests the iteration getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetIteration()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setIteration($iteration = $this->createIteration());

        $this->assertSame($iteration, $reflect->_getIteration());
    }
}
