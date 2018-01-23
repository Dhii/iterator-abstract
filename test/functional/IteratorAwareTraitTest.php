<?php

namespace Dhii\Iterator\FuncTest;

use \InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;
use Xpmock\TestCase;
use Dhii\Iterator\IteratorAwareTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class IteratorAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\IteratorAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods(['__', '_createInvalidArgumentException'])
                     ->getMockForTrait();

        $mock->method('__')->willReturnArgument(0);
        $mock->method('_createInvalidArgumentException')->willReturnCallback(
            function($msg = '', $code = 0, $prev = null) {
                return new InvalidArgumentException($msg, $code, $prev);
            }
        );

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetIterator()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $input = $this->getMockForAbstractClass('Dhii\Iterator\IteratorInterface');

        $reflect->_setIterator($input);

        $this->assertSame($input, $reflect->_getIterator(), 'Set and retrieved value are not the same.');
    }

    /**
     * Tests the getter and setter methods with an invalid value to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetIteratorInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $input = new stdClass();

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setIterator($input);
    }
}
