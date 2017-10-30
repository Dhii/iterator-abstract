<?php

namespace Dhii\Iterator;

use InvalidArgumentException;
use Iterator;
use Dhii\Iterator\Exception\IteratingExceptionInterface;
use Traversable;

/**
 * Common functionality for iterators.
 *
 * @since [*next-version*]
 */
abstract class AbstractIterator
{
    /**
     * Parameter-less constructor.
     *
     * Invoke this in actual constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
    }

    /**
     * Advances the iterator to the next element.
     *
     * @since [*next-version*]
     *
     * @throws IteratingExceptionInterface If advancing is not possible.
     */
    protected function _next()
    {
        $this->_setIteration($this->_loop());

        return $this;
    }

    /**
     * Resets the iterator.
     *
     * @see Iterator::rewind()
     * @since [*next-version*]
     */
    protected function _rewind()
    {
        $this->_setIteration($this->_reset());

        return $this;
    }

    /**
     * Retrieves the key of the current iteration.
     *
     * @since [*next-version*]
     * @see Iterator::key()
     * @since [*next-version*]
     *
     * @return string|null The key, if iterating; otherwise, null.
     */
    protected function _key()
    {
        return $this->_getIteration()->getKey();
    }

    /**
     * Retrieves the value of the current iteration.
     *
     * @since [*next-version*]
     * @see Iterator::current()
     *
     * @return mixed The value.
     */
    protected function _value()
    {
        return $this->_getIteration()->getValue();
    }

    /**
     * Determines whether the current state of the iterator is valid.
     *
     * @since [*next-version*]
     * @see Iterator::valid()
     *
     * @return bool True if current state is valid; false otherwise;
     */
    protected function _valid()
    {
        $key = $this->_key();

        return !is_null($key);
    }

    /**
     * Retrieves the current iteration.
     *
     * @since [*next-version*]
     *
     * @return IterationInterface|null The current iteration, if any.
     */
    abstract protected function _getIteration();

    /**
     * Assigns an iteration to this instance.
     *
     * @since [*next-version*]
     * 
     * @param IterationInterface|null $iteration The iteration to set.
     *
     * @throws InvalidArgumentException If not a valid iteration
     */
    abstract protected function _setIteration(IterationInterface $iteration = null);

    /**
     * Computes a reset state.
     *
     * @since [*next-version*]
     *
     * @return IterationInterface The iteration that represents the new state.
     */
    abstract protected function _reset();

    /**
     * Advances the iterator and computes the new state.
     *
     * @since [*next-version*]
     *
     * @return IterationInterface The iteration that represents the new state.
     */
    abstract protected function _loop();
}
