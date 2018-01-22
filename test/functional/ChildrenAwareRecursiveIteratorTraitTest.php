<?php

namespace Dhii\Iterator\FuncTest;

use Dhii\Iterator\ChildrenAwareRecursiveIteratorTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ChildrenAwareRecursiveIteratorTraitTest extends TestCase
{
    /**
     * The classname of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\ChildrenAwareRecursiveIteratorTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createInstance()
    {
        // Methods to mock
        $methods = [];
        // Create mock
        $mock = $this->getMockForTrait(
            static::TEST_SUBJECT_CLASSNAME,
            [],
            '',
            true,
            true,
            true,
            $methods
        );

        return $mock;
    }

    /**
     * Creates a new children aware mock instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param array $children The children of the instance to create.
     *
     * @return ChildrenAwareInterface
     */
    public function createChildrenAwareInstance($children = [])
    {
        $mock = $this->mock('Dhii\\Data\\Hierarchy\\ChildrenAwareInterface')
                     ->getChildren($children)
                     ->hasChildren(count($children) > 0);

        return $mock->new();
    }

    /**
     * Tests the method that determines whether an iteration item has children.
     *
     * @since [*next-version*]
     */
    public function testIsElementHasChildren()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $noChildren = $this->createChildrenAwareInstance([]);
        $this->assertFalse($reflect->_isElementHasChildren($noChildren));

        $hasChildren = $this->createChildrenAwareInstance([1, 'test', 59.99]);
        $this->assertTrue($reflect->_isElementHasChildren($hasChildren));

        $notChildrenAware = ['just', 'some', 'array'];
        $this->assertFalse($reflect->_isElementHasChildren($notChildrenAware));
    }

    /**
     * Tests the method that determines the children for an iteration item.
     *
     * @since [*next-version*]
     */
    public function testIsElementGetChildren()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $children = [1, 'two', 'test', 'subArray' => ['a', 'b', 'c']];
        $caInstance = $this->createChildrenAwareInstance($children);
        $this->assertEquals($children, $reflect->_getElementChildren($caInstance));

        $notChildrenAware = ['just', 'some', 'array'];
        $this->assertEmpty($reflect->_getElementChildren($notChildrenAware));
    }
}
