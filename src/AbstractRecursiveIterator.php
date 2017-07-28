<?php

namespace Dhii\Iterator;

use Iterator;

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
        return count($this->stack) > 0;
    }

    /**
     * Determines if an element has children that this iterator could recurse into.
     *
     * @since [*next-version*]
     *
     * @return bool True of the element has children; false otherwise.
     */
    abstract protected function _isElementHasChildren($value);

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

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function &_getCurrentIterable()
    {
        $parent =& $this->_getLatestParent();

        return $parent;
    }
}
