<?php

namespace Yard\IRMA;

use ArrayAccess;
use InvalidArgumentException;
use OutOfBoundsException;

class AttributeCollection implements ArrayAccess
{
    /**
     * @var Attribute[]
     */
    private $attributes = [];

    /**
     * @return AttributeCollection
     */
    public static function make()
    {
        return new static;
    }

    /**
     * @param Attribute $value
     * @return AttributeCollection
     */
    public function add(Attribute $value)
    {
        $this->attributes[] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return array_map(function (Attribute $attribute) {
            return $attribute->getId();
        }, $this->attributes);
    }

    /**
     * Determine if attribute id exists in the collection.
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($offset == $attribute->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get attribute from collection.
     *
     * @param mixed $offset
     * @return Attribute
     */
    public function offsetGet($offset)
    {
        foreach ($this->attributes as $attribute) {
            if ($offset == $attribute->getId()) {
                return $attribute;
            }
        }

        throw new OutOfBoundsException("Attribute with ID $offset does not exist.");
    }

    /**
     * @param mixed $offset
     * @param Attribute $attribute
     * @return void
     */
    public function offsetSet($offset, $attribute): void
    {
        if (!$attribute instanceof Attribute) {
            throw new InvalidArgumentException('Attribute must be of type ' . Attribute::class);
        }

        $this->add($attribute);
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }
}
