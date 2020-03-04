<?php

namespace Yard\GravityForms\BAGAddress;

use StdClass;

class BAGEntity
{
    protected $data;

    public function __construct(StdClass $data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return $this->data->{$key} ?? null;
    }
}
