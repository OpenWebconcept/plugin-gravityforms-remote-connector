<?php

namespace Yard\OpenZaak;

class KeyValuePair
{
    /**
     * Data array
     *
     * @var array
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data   = $data;
    }

    /**
     * Static constructor
     *
     * @param array $data
     *
     * @return self
     */
    public static function make(array $data = []): self
    {
        return new self($data);
    }

    /**
     * Return the key.
     *
     * @return string
     */
    public function key(): string
    {
        return $this->data['name'] ?? '';
    }

    /**
     * Return the value.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->data['value'] ?? '';
    }
}
