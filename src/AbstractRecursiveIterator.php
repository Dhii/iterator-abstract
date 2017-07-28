<?php

namespace Dhii\Iterator;

use Iterator;
use Traversable;

/**
 * Common functionality for iterators which visit nodes more than 1 level deep.
 *
 * @since [*next-version*]
 */
abstract class AbstractRecursiveIterator extends AbstractIterator
{
    /**
     * The stack of parents needed to maintain hierarchy path trace.
     *
     * @since [*next-version*]
     *
     * @var Iterator[]|array[]
     */
    protected $parents;

    /**
     * Resets the values.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
        $this->_resetParents();
        parent::_construct();
    }

    /**
     * Adds an iterable parent onto the stack.
     * 
     * The stack is there to maintain a trace of hierarchy.
     *
     * @since [*next-version*]
     *
     * @param Iterator|array $parent The parent.
     */
    protected function _pushParent(&$parent)
    {
        array_unshift($this->parents, $parent);
    }

    /**
     * Removes an iterable parent from the stack.
     *
     * The stack is there to maintain a trace of hierarchy.
     *
     * @since [*next-version*]
     */
    protected function _popParent()
    {
        array_shift($this->parents);
    }

    /**
     * Checks if there are iterable parents on the stack.
     *
     * @since [*next-version*]
     *
     * @return bool True if there is at least one iterable parent on the stack, false if there are none.
     */
    protected function _hasParents()
    {
        return count($this->parents) > 0;
    }

    /**
     * Returns the parent stack to its original state.
     *
     * @since [*next-version*]
     *
     * @return $this
     */
    protected function _resetParents()
    {
        $this->parents = [];

        return $this;
    }

    /**
     * Retrieves the parent which is at the top of the stack.
     *
     * @since [*next-version*]
     *
     * @return array|Iterator The iterable parent.
     */
    protected function &_getTopmostParent()
    {
        if (isset($this->parents[0])) {
            return $this->parents[0];
        }

        // Only variables may be returned by reference
        $empty = [];

        return $empty;
    }

    /**
     * Retrieves the iterable that this iterator should be iterating over.
     *
     * @since [*next-version*]
     *
     * @return Traversable|array The iterable.
     */
    protected function &_getCurrentIterable()
    {
        $iterable =& $this->_getTopmostParent();

        return $iterable;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _loop()
    {
        // More complex logic goes here
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _reset()
    {
        // More complex logic goes here
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _valid()
    {
        return $this->_hasParents();
    }

    /**
     * Creates an iteration instance for the current state of a given iterable.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable $iterable The iterable.
     *
     * @return IterationInterface
     */
    protected function _createCurrentIteration(&$iterable)
    {
        return $this->_createIteration(
            $this->_getCurrentIterableKey($iterable),
            $this->_getCurrentIterableValue($iterable)
        );
    }

    /**
     * Retrieves the key for the current element of an iterable.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable $iterable The iterable.
     *
     * @return mixed The current key.
     */
    protected function _getCurrentIterableKey(&$iterable)
    {
        return key($iterable);
    }

    /**
     * Retrieves the value for the current element of an iterable.
     *
     * @since [*next-version*]
     *
     * @param array|Traversable $iterable The iterable.
     *
     * @return mixed The current value.
     */
    protected function _getCurrentIterableValue(&$iterable)
    {
        return current($iterable);
    }

    /**
     * Determines if an element has children that this iterator could recurse into.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The element to check.
     *
     * @return bool True of the element has children; false otherwise.
     */
    abstract protected function _isElementHasChildren($value);

    /**
     * Retrieves the children of a element.
     *
     * @since [*next-version*]
     *
     * @param mixed $value The element whose children to retrieve.
     *
     * @return array|Traversable The children of the element.
     */
    abstract protected function _getElementChildren($value);

    /**
     * Determines if the currently selected modes include a specific mode.
     *
     * @since [*next-version*]
     *
     * @param int $mode The mode to check for.
     *
     * @return bool True if mode selected; otherwise, false.
     */
    abstract protected function _isMode($mode);
}
