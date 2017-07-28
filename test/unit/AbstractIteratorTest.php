<?php

namespace Dhii\Iterator\UnitTest;

use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Iterator\AbstractIterator}.
 *
 * @TODO Search and replace tokens that begin with underscore.
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
     * @return Dhii\Iterator\AbstractIterator
     */
    public function createInstance($data = array(), $key = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->_key(function () use ($key) {
                    return $key;
                })
                ->_value()
                ->_getIteration()
                ->_setIteration()
                ->_createIteration()
                ->_getCurrentIterable(function &() use (&$data) {
                    return $data;
                })
                ->new();

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

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME, $subject, 'Subject is not a valid instance.'
        );
    }

    /**
     * Tests that a valid key is determined to be valid.
     *
     * @since [*next-version*]
     */
    public function testValid()
    {
        $subject = $this->createInstance(array(), uniqid('key-'));
        $_subject = $this->reflect($subject);

        $result = $_subject->_valid();

        $this->assertTrue($result, 'Subject wrongly determined to be invalid');
    }

    /**
     * Tests that an invalid key is determined to be invalid.
     *
     * @since [*next-version*]
     */
    public function testNotValid()
    {
        $subject = $this->createInstance(array(), null);
        $_subject = $this->reflect($subject);

        $result = $_subject->_valid();

        $this->assertFalse($result, 'Subject wrongly determined to be valid');
    }
}
