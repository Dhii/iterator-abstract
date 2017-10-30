<?php

namespace Dhii\Iterator\UnitTest;

use Dhii\Iterator\IterationInterface;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see \Dhii\Iterator\AbstractIterator}.
 *
 * @since [*next-version*]
 */
class AbstractIteratorTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Iterator\\AbstractIterator';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance(array $methods = [])
    {
        $builder = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                        ->setMethods(
                            array_merge(
                                [
                                    '_getIteration',
                                    '_setIteration',
                                    '_nextIteration',
                                    '_resetIteration',
                                ],
                                $methods
                            )
                        );

        return $builder->getMockForAbstractClass();
    }

    /**
     * Creates a new iteration mock instance.
     *
     * @since [*next-version*]
     *
     * @param string $key   The iteration key.
     * @param mixed  $value The iteration value.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createIteration($key, $value)
    {
        $builder = $this->getMockBuilder('Dhii\Iterator\IterationInterface')
                        ->setMethods(
                            [
                                'getKey',
                                'getValue',
                            ]
                        );

        return $builder->getMockForAbstractClass();
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'Subject is not a valid instance.'
        );
    }

    /**
     * Tests the rewind method to ensure that the reset iteration method is invoked and the iteration is updated.
     *
     * @since [*next-version*]
     */
    public function testRewind()
    {
        $subject   = $this->createInstance();
        $reflect   = $this->reflect($subject);
        $iteration = $this->createIteration(uniqid('key-'), new stdClass());

        $subject->expects($this->once())
                ->method('_resetIteration')
                ->willReturn($iteration);

        $subject->expects($this->once())
                ->method('_setIteration')
                ->with($iteration);

        $reflect->_rewind();
    }

    /**
     * Tests the next method to ensure that the next iteration method is invoked and the iteration is updated.
     *
     * @since [*next-version*]
     */
    public function testNext()
    {
        $subject   = $this->createInstance();
        $reflect   = $this->reflect($subject);
        $iteration = $this->createIteration(uniqid('key-'), new stdClass());

        $subject->expects($this->once())
                ->method('_nextIteration')
                ->willReturn($iteration);

        $subject->expects($this->once())
                ->method('_setIteration')
                ->with($iteration);

        $reflect->_next();
    }

    /**
     * Tests the key method to ensure that the iteration key is returned.
     *
     * @since [*next-version*]
     */
    public function testKey()
    {
        $subject   = $this->createInstance();
        $reflect   = $this->reflect($subject);
        $iterKey   = uniqid('key-');
        $iterValue = new stdClass();
        $iteration = $this->createIteration($iterKey, $iterValue);

        $iteration->expects($this->once())
                  ->method('getKey')
                  ->willReturn($iterKey);

        $subject->expects($this->once())
                ->method('_getIteration')
                ->willReturn($iteration);

        $this->assertEquals($iterKey, $reflect->_key(), 'Expected and retrieved keys are not the same.');
    }

    /**
     * Tests the value method to ensure that the iteration value is returned.
     *
     * @since [*next-version*]
     */
    public function testValue()
    {
        $subject   = $this->createInstance();
        $reflect   = $this->reflect($subject);
        $iterKey   = uniqid('key-');
        $iterValue = new stdClass();
        $iteration = $this->createIteration($iterKey, $iterValue);

        $iteration->expects($this->once())
                  ->method('getValue')
                  ->willReturn($iterValue);

        $subject->expects($this->once())
                ->method('_getIteration')
                ->willReturn($iteration);

        $this->assertEquals($iterValue, $reflect->_value(), 'Expected and retrieved keys are not the same.');
    }

    /**
     * Tests that a valid key is determined to be valid.
     *
     * @since [*next-version*]
     */
    public function testValid()
    {
        $subject = $this->createInstance(['_key']);
        $reflect = $this->reflect($subject);
        $iterKey = uniqid('key-');

        $subject->expects($this->once())
                ->method('_key')
                ->willReturn($iterKey);

        $this->assertTrue($reflect->_valid(), 'Subject wrongly determined to be invalid');
    }

    /**
     * Tests that an invalid key is determined to be invalid.
     *
     * @since [*next-version*]
     */
    public function testNotValid()
    {
        $subject = $this->createInstance(['_key']);
        $reflect = $this->reflect($subject);

        $subject->expects($this->once())
                ->method('_key')
                ->willReturn(null);

        $this->assertFalse($reflect->_valid(), 'Subject wrongly determined to be valid');
    }
}
