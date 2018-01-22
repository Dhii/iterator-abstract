<?php

namespace Dhii\Iterator\FuncTest;

use Dhii\Iterator\RecursiveIteratorTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class RecursiveIteratorTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\RecursiveIteratorTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array  $data
     * @param string $key
     *
     * @return MockObject
     */
    public function createInstance($data = [], $key = null)
    {
        $builder = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                        ->setMethods(
                            [
                                '_key',
                                '_value',
                                '_getIteration',
                                '_setIteration',
                                '_createIteration',
                                '_getCurrentIterable',
                                '_isMode',
                                '_getCurrentIterableKey',
                                '_getCurrentIterableValue',
                                '_isElementHasChildren',
                                '_getElementChildren',
                                '_getElementPathSegment',
                                '_createRecursiveIteration',
                                '_getInitialParentIterable',
                            ]
                        );

        $mock = $builder->getMockForTrait();
        $mock->method('_key')->willReturn($key);
        $mock->method('_getElementChildren')->willReturnArgument(0);
        $mock->method('_getCurrentIterable')->willReturnCallback(
            function &() use (&$data) {
                return $data;
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
            'Subject is not a valid instance.'
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
        $reflect = $this->reflect($subject);

        $_pushParent = new \ReflectionMethod($subject, '_pushParent');
        $_pushParent->setAccessible(true);
        $_getTopmostParent = new \ReflectionMethod($subject, '_getTopmostParent');
        $_getTopmostParent->setAccessible(true);

        $reflect->parents = [];
        $reflect->pathSegments = [];

        $result1 = $reflect->_getTopmostParent();
        $this->assertEmpty($result1, 'Wrong initial top-most parent');

        $array2 = ['Miggy'];
        $_pushParent->invokeArgs($subject, [&$array2]);
        $result2 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array2, $result2, 'Wrong second top-most parent');

        $array3 = ['Tony'];
        $_pushParent->invokeArgs($subject, [&$array3]);
        $result3 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array3, $result3, 'Wrong third top-most parent');

        $reflect->_popParent();
        $result4 = $_getTopmostParent->invoke($subject);
        $this->assertSame($array2, $result4, 'Wrong top-most parent after pop');
    }
}
