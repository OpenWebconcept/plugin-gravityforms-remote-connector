<?php

namespace Yard\OpenZaak\GravityForms\Fields;

use Yard\OpenZaak\AttributesManager;

abstract class AbstractField
{
    /**
     * Fields object
     *
     * @var object
     */
    protected $field;

    /**
     * Entry array
     *
     * @var array
     */
    protected $entry = [];

    /**
     * Attributes array.
     *
     * @var AttributesManager
     */
    protected $attributes;

    public function __construct(object $field, array $entry, AttributesManager $attributes)
    {
        $this->field      = $field;
        $this->entry      = $entry;
        $this->attributes = $attributes;
    }

    public function getPropertyName(): string
    {
        return trim($this->field->casePropertyName) ?? '';
    }

    public function getPropertyValue(): string
    {
        return trim(rgar($this->entry, (string) $this->field->id));
    }
}
