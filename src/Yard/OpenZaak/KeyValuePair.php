<?php

namespace Yard\OpenZaak;

class KeyValuePair
{
    protected $data;

    public function __construct($data)
    {
        $this->data   = $data;
    }

    public static function make($data)
    {
        return new self($data);
    }

    public function key()
    {
        return $this->data['name'];
    }

    public function value()
    {
        return $this->data['value'];
    }
}
