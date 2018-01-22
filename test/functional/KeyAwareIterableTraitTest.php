<?php

namespace Dhii\Iterator\FuncTest;

use Dhii\Data\KeyAwareInterface;
use Dhii\Iterator\KeyAwareIterableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use ReflectionMethod;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class KeyAwareIterableTraitTest extends TestCase
{
    /**
     * The classname of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Iterator\KeyAwareIterableTrait';

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
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

        $mock->method('_getCurrentIterableValue')
             ->willReturnCallback(
                 function(&$iterable) {
                     return current($iterable);
                 }
             );

        return $mock;
    }

    /**
     * Creates a key-aware mock instance for testing purposes.
     *
     * @since [*next-version*]
     *
     * @param string $key The key.
     *
     * @return KeyAwareInterface
     */
    public function createKeyAwareInstance($key)
    {
        $mock = $this->mock('Dhii\\Data\\KeyAwareInterface')
                     ->getKey($key);

        return $mock->new();
    }

    /**
     * Tests the current iterable key retrieval method.
     *
     * @since [*next-version*]
     */
    public function testGetCurrentIterableKey()
    {
        $subject = $this->createInstance();
        $reflect = new ReflectionMethod($subject, '_getCurrentIterableKey');
        $reflect->setAccessible(true);

        $iterable = [
            $this->createKeyAwareInstance($key1 = 'foo'),
            $this->createKeyAwareInstance($key2 = 'test'),
        ];

        $this->assertEquals($key1, $reflect->invokeArgs($subject, [&$iterable]));

        next($iterable);

        $this->assertEquals($key2, $reflect->invokeArgs($subject, [&$iterable]));
    }

    /**
     * Tests the element path segment retrieval method.
     *
     * @since [*next-version*]
     */
    public function testGetElementPathSegment()
    {
        $subject = $this->createInstance();
        $reflect = new ReflectionMethod($subject, '_getElementPathSegment');
        $reflect->setAccessible(true);

        $keyAware = $this->createKeyAwareInstance('foobar-key');
        $this->assertEquals(
            $keyAware->getKey(),
            $reflect->invokeArgs(
                $subject,
                [
                    'some-key', // key
                    $keyAware  // value
                ]
            )
        );

        $fallbackKey = 'fallback-key';
        $this->assertEquals(
            $fallbackKey,
            $reflect->invokeArgs(
                $subject,
                [
                    $fallbackKey,           // key
                    'no-a-key-aware-object' // value
                ]
            )
        );
    }
}
