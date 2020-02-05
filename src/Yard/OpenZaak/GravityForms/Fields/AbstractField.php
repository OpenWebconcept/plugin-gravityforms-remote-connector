<?php

namespace Yard\OpenZaak\GravityForms\Fields;

abstract class AbstractField
{
    protected $field;

    protected $entry = [];

    protected $attributes;

    public function __construct(object $field, array $entry, $attributes)
    {
        $this->field      = $field;
        $this->entry      = $entry;
        $this->attributes = $attributes;
    }

    public function getPropertyName(): string
    {
        return trim($this->field['casePropertyName']) ?? '';
    }

    public function getPropertyValue(): string
    {
        return trim(rgar($this->entry, (string) $this->field['id']));
    }
}
