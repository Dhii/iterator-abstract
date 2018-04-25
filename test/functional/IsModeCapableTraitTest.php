<?php

namespace Dhii\Iterator\FuncTest;

use Dhii\Iterator\IsModeCapableTrait as TestSubject;
use Xpmock\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class IsModeCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\IsModeCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues(
            $methods,
            [
                '_getMode',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
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
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the mode checker method to assert whether it returns true when the given mode is equivalent to the mode
     * returned by `_getMode()`.
     *
     * @since [*next-version*]
     */
    public function testIsModeTrue()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $mode = uniqid('mode-');
        $arg = $mode;

        $subject->method('_getMode')->willReturn($mode);

        $this->assertTrue($reflect->_isMode($arg));
    }

    /**
     * Tests the mode checker method to assert whether it returns false when the given mode is different from the mode
     * returned by `_getMode()`.
     *
     * @since [*next-version*]
     */
    public function testIsModeFalse()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $mode = uniqid('mode-');
        $arg = uniqid('mode-');

        $subject->method('_getMode')->willReturn($mode);

        $this->assertFalse($reflect->_isMode($arg));
    }
}
