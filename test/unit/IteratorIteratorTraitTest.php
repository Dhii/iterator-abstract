<?php

namespace Dhii\Iterator\UnitTest;

use Dhii\Iterator\IterationInterface;
use Dhii\Iterator\IteratorIteratorTrait as TestSubject;
use InvalidArgumentException;
use Iterator;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class IteratorIteratorTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\IteratorIteratorTrait';

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
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
            ->will($this->returnArgument(0));

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
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a mock that uses traits.
     *
     * This is particularly useful for testing integration between multiple traits.
     *
     * @since [*next-version*]
     *
     * @param string[] $traitNames Names of the traits for the mock to use.
     *
     * @return MockBuilder The builder for a mock of an object that uses the traits.
     */
    public function mockTraits($traitNames = [])
    {
        $paddingClassName = uniqid('Traits');
        $definition = vsprintf('abstract class %1$s {%2$s}', [
            $paddingClassName,
            implode(
                ' ',
                array_map(
                    function ($v) {
                        return vsprintf('use %1$s;', [$v]);
                    },
                    $traitNames)),
        ]);
        var_dump($definition);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return InvalidArgumentException|MockObject The new exception.
     */
    public function createInvalidArgumentException($message = '')
    {
        $mock = $this->getMockBuilder('InvalidArgumentException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new iterator.
     *
     * @since [*next-version*]
     *
     * @param array|null $methods The methods to mock.
     *
     * @return MockObject|Iterator The new iterator.
     */
    public function createIterator($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            'current',
            'key',
            'next',
            'rewind',
            'valid',
        ]);

        $mock = $this->getMockBuilder('Iterator')
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new iteration.
     *
     * @since [*next-version*]
     *
     * @param array|null $methods The methods to mock.
     *
     * @return MockObject|IterationInterface The new iteration.
     */
    public function createIteration($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            'getKey',
            'getValue',
        ]);

        $mock = $this->getMockBuilder('Dhii\Iterator\IterationInterface')
            ->setMethods($methods)
            ->getMock();

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
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests whether `_advanceTracker()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testAdvanceTracker()
    {
        $tracker = $this->createIterator(['next']);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('next');

        $_subject->_advanceTracker($tracker);
    }

    /**
     * Tests whether `_advanceTracker()` fails correctly when given an invalid tracker.
     *
     * @since [*next-version*]
     */
    public function testAdvanceTrackerFailureInvalidTracker()
    {
        $tracker = uniqid('tracker');
        $exception = $this->createInvalidArgumentException('Invalid tracker');
        $subject = $this->createInstance(['_createInvalidArgumentException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_createInvalidArgumentException')
            ->with(
                $this->isType('string'),
                null,
                null,
                $tracker
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_advanceTracker($tracker);
    }

    /**
     * Tests whether `_resetTracker()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testResetTracker()
    {
        $tracker = $this->createIterator(['next']);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('rewind');

        $_subject->_resetTracker($tracker);
    }

    /**
     * Tests whether `_resetTracker()` fails correctly when given an invalid tracker.
     *
     * @since [*next-version*]
     */
    public function testResetTrackerFailureInvalidTracker()
    {
        $tracker = uniqid('tracker');
        $exception = $this->createInvalidArgumentException('Invalid tracker');
        $subject = $this->createInstance(['_createInvalidArgumentException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_createInvalidArgumentException')
            ->with(
                $this->isType('string'),
                null,
                null,
                $tracker
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_resetTracker($tracker);
    }

    /**
     * Tests whether `_createIterationFromTracker()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testCreateIterationFromTracker()
    {
        $key = uniqid('key');
        $val = uniqid('val');
        $tracker = $this->createIterator([]);
        $iteration = $this->createIteration([]);
        $subject = $this->createInstance(['_calculateKey', '_calculateValue', '_createIteration']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_calculateKey')
            ->with($tracker)
            ->will($this->returnValue($key));
        $subject->expects($this->exactly(1))
            ->method('_calculateValue')
            ->with($tracker)
            ->will($this->returnValue($val));
        $subject->expects($this->exactly(1))
            ->method('_createIteration')
            ->with($key, $val)
            ->will($this->returnValue($iteration));

        $result = $_subject->_createIterationFromTracker($tracker);
        $this->assertSame($iteration, $result, 'Wrong iteration produced from tracker');
    }

    /**
     * Tests whether `_createIterationFromTracker()` fails correctly when given an invalid tracker.
     *
     * @since [*next-version*]
     */
    public function testCreateIterationFromTrackerFailureInvalidTracker()
    {
        $tracker = uniqid('tracker');
        $exception = $this->createInvalidArgumentException('Invalid tracker');
        $subject = $this->createInstance(['_createInvalidArgumentException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_createInvalidArgumentException')
            ->with(
                $this->isType('string'),
                null,
                null,
                $tracker
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_createIterationFromTracker($tracker);
    }

    /**
     * Tests whether `_calculateKey()` works as expected when tracker is valid.
     *
     * @since [*next-version*]
     */
    public function testCalculateKeyExplicit()
    {
        $key = uniqid('key');
        $tracker = $this->createIterator([]);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('valid')
            ->will($this->returnValue(true));
        $tracker->expects($this->exactly(1))
            ->method('key')
            ->will($this->returnValue($key));

        $result = $_subject->_calculateKey($tracker);
        $this->assertEquals($key, $result, 'Wrong explicit key calculated');
    }

    /**
     * Tests whether `_calculateValue()` works as expected when tracker is invalid.
     *
     * @since [*next-version*]
     */
    public function testCalculateKeyDefault()
    {
        $key = null;
        $tracker = $this->createIterator([]);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('valid')
            ->will($this->returnValue(false));

        $result = $_subject->_calculateKey($tracker);
        $this->assertEquals($key, $result, 'Wrong default key calculated');
    }

    /**
     * Tests whether `_calculateValue()` works as expected when tracker is valid.
     *
     * @since [*next-version*]
     */
    public function testCalculateValueExplicit()
    {
        $val = uniqid('val');
        $tracker = $this->createIterator([]);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('valid')
            ->will($this->returnValue(true));
        $tracker->expects($this->exactly(1))
            ->method('current')
            ->will($this->returnValue($val));

        $result = $_subject->_calculateValue($tracker);
        $this->assertEquals($val, $result, 'Wrong explicit value calculated');
    }

    /**
     * Tests whether `_calculateValue()` works as expected when tracker is invalid.
     *
     * @since [*next-version*]
     */
    public function testCalculateValueDefault()
    {
        $val = null;
        $tracker = $this->createIterator([]);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $tracker->expects($this->exactly(1))
            ->method('valid')
            ->will($this->returnValue(false));

        $result = $_subject->_calculateValue($tracker);
        $this->assertEquals($val, $result, 'Wrong default value calculated');
    }
}
