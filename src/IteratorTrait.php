<?php

namespace Dhii\Iterator;

use Dhii\Iterator\Exception\IteratingExceptionInterface;

/**
 * Common functionality for objects that can iterate.
 *
 * @since [*next-version*]
 */
trait IteratorTrait
{
    /**
     * Resets the iterator.
     *
     * @since [*next-version*]
     * @see Iterator::rewind()
     */
    protected function _rewind()
    {
        $this->_setIteration($this->_reset());
    }

    /**
     * Advances the iterator to the next element.
     *
     * @since [*next-version*]
     * @see Iterator::next()
     *
     * @throws IteratingExceptionInterface If advancing is not possible.
     */
    protected function _next()
    {
        $this->_setIteration($this->_loop());
    }

    /**
     * Retrieves the key of the current iteration.
     *
     * @since [*next-version*]
     * @see Iterator::key()
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
        return $this->_key() !== null;
    }

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
     */
    abstract protected function _setIteration(IterationInterface $iteration = null);
}
