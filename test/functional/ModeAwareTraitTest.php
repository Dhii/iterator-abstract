<?php

namespace Dhii\Iterator\FuncTest;

use Dhii\Iterator\ModeAwareTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ModeAwareTraitTest extends TestCase
{
    /**
     * The classname of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Iterator\\ModeAwareTrait';

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
     * Tests the mode getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetMode()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $reflect->_setMode($mode = rand(0, 100));
        $this->assertEquals($mode, $reflect->_getMode());
    }
}
