<?php

namespace Dhii\Iterator\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see Dhii\Iterator\AbstractRecursiveIterator}.
 *
 * @TODO Search and replace tokens that begin with underscore.
 *
 * @since [*next-version*]
 */
class AbstractRecursiveIteratorTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\Iterator\\AbstractRecursiveIterator';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return Dhii\Iterator\AbstractRecursiveIterator
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
                ->_isMode()
                ->_isElementHasChildren()
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
     * All the reflections and references are there to go around
     * PHPUnit's problems.
     *
     * @since [*next-version*]
     */
    public function testParents()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $_pushParent = new \ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_pushParent');
        $_pushParent->setAccessible(true);
        $_getTopmostParent = new \ReflectionMethod(static::TEST_SUBJECT_CLASSNAME, '_getTopmostParent');
        $_getTopmostParent->setAccessible(true);

        $_subject->parents = [];

        $result1 = $_subject->_getTopmostParent();
        $this->assertEmpty($result1, 'Wrong initial top-most parent');

        $array2 = array('Miggy');
        $_pushParent->invokeArgs($subject, [&$array2]);
        $result2 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array2, $result2, 'Wrong second top-most parent');

        $array3 = array('Tony');
        $_pushParent->invokeArgs($subject, [&$array3]);
        $result3 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array3, $result3, 'Wrong third top-most parent');

        $_subject->_popParent();
        $result4 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array2, $result4, 'Wrong top-most parent after pop');
    }
}
