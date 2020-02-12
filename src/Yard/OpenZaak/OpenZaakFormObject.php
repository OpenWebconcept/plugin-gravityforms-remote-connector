<?php

namespace Yard\OpenZaak;

use Yard\OpenZaak\GravityForms\Fields\AbstractField;
use Yard\OpenZaak\GravityForms\Fields\OpenZaak;

class OpenZaakFormObject
{
    /**
     * Fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Entry.
     *
     * @var array
     */
    protected $entry = [];

    /**
     * Attributes
     *
     * @var AttributesManager
     */
    protected $attributes;

    final public function __construct(array $fields = [], array $entry = [])
    {
        $this->fields     = $fields;
        $this->entry      = $entry;
        $this->attributes = AttributesManager::make();
    }

    /**
     * Static constructor
     *
     * @param array $fields
     * @param array $entry
     *
     * @return self
     */
    public static function make(array $fields = [], array $entry = []): self
    {
        return new static($fields, $entry);
    }

    public function buildFields(): array
    {
        $formEntries   = [];
        foreach ($this->fields as $field) {
            if (!$this->hasPropertyName($field)) {
                continue;
            }

            $field                                   = $this->factoryField($field);
            $formEntries[$field->getPropertyName()]  =  $field->getPropertyValue();
        }

        return $formEntries;
    }

    protected function factoryField(object $field): AbstractField
    {
        $className  = get_class($field);
        $class      = 'Yard\OpenZaak\GravityForms\Fields\\'. str_replace('GF_Field_', '', $className);
        if (class_exists($class)) {
            $field = new $class($field, $this->entry, $this->attributes);
        } else {
            $field = new OpenZaak($field, $this->entry, $this->attributes);
        }

        return $field;
    }

    /**
     * Check if object contains 'propertyName'.
     *
     * @param object $field
     *
     * @return boolean
     */
    protected function hasPropertyName(object $field): bool
    {
        return isset($field->casePropertyName);
    }

    /**
     * Return data to json
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->buildFields());
    }

    /**
     * Return data to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return json_decode($this->toJson(), true);
    }
}
