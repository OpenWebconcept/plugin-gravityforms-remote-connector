<?php

namespace Yard\OpenZaak\GravityForms\Fields;

class Hidden extends AbstractField
{
    public function getPropertyValue(): string
    {
        return trim($this->attributes->find($this->getPropertyName(), '', 'value'));
    }
}
