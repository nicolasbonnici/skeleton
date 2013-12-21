<?php

/**
 * Collection class with basic features
 * @author Antoine <antoine.preveaux@bazarchic.com>
 * @version 1.0.0 - 2013-07-12 - Antoine <antoine.preveaux@bazarchic.com>
 */
class core_BaseCollection implements Iterator
{
    /**
     * List of elements in collection
     * @var array
     */
    protected $aElements = array();

    /**
     * Constructor
     * @param array $aElements Collection elements
     */
    public function __construct(array $aElements = array())
    {
        $this->aElements = $aElements;
    }

    /**
     * @see Iterator::current()
     */
    public function current()
    {
        return current($this->aElements);
    }

    /**
     * @see Iterator::key(
     *
     *
     */
    public function key()
    {
        return keyreset($this->aElements);
    }

    /**
     * @see Iterator::next()
     */
    public function next()
    {
        next($this->aElements);
    }

    /**
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->aElements);
    }

    /**
     * @see Iterator::valid()
     */
    public function valid()
    {
        return ($this->current() !== false);
    }

    /**
     * Count the number of elements in collection
     * @return integer Number of elements contained in collection
     */
    public function count()
    {
        return count($this->aElements);
    }

    /**
     * Sort collection elements by value
     * @return boolean TRUE on success, otherwise FALSE
     */
    public function sort()
    {
        return asort($this->aElements);
    }

    /**
     * Sort collection elements by value
     * @return boolean TRUE on success, otherwise FALSE
     */
    public function ksort()
    {
        return ksort($this->aElements);
    }

    /**
     * Add element to collection
     * @param integer|string $mKey Element's key
     * @param mixed $mValue Element's value
     */
    public function add($mKey, $mValue)
    {
        if (is_int($mKey) || !empty($mKey)) {
            $this->aElements[$mKey] = $mValue;
        }
    }

    /**
     * Remove an element from collection
     * @param mixed $mKey Key of the element to remove
     */
    public function remove($mKey)
    {
        if (isset($this->aElements[$mKey])) {
            unset($this->aElements[$mKey]);
        }
    }

    /**
     * Retrieve an element of the collection by its key
     * @param integer|string $mKey Element's key
     * @return mixed Element if existing, otherwise null
     */
    public function get($mKey)
    {
        assert('is_int($mKey) || !empty($mKey)');

        return isset($this->aElements[$mKey]) ? $this->aElements[$mKey] : null;
    }

    /**
     * Reset collection
     */
    public function reset()
    {
        $this->aElements = array();
    }

    /**
     * Check whether given key is part of collection
     * @param mixed $mKey Key
     * @return boolean TRUE if element is part of collection, otherwise FALSE
     */
    public function inArray($mKey)
    {
        return array_key_exists($mKey, $this->aElements);
    }
}
