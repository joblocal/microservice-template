<?php

namespace App\Models\Remote;

use ArrayAccess;

class ApplicationModel implements ArrayAccess
{
    /**
     * api endpoint name for the RemoteModel
     *
     * @var string
     */
    protected static $endpoint;

    /**
     * attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * set an attribute's value by it's key
     *
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * get the attribute value
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * returns all attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * fills attributes
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    /**
     * Dynamically get attributes
     *
     * @param mixed $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically sets attributes
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * ArrayAccess offsetSet
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    /**
     * ArrayAccess offsetExists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * ArrayAccess offsetUnset
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * ArrayAccess offsetGet
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset] ?? null;
    }
}
