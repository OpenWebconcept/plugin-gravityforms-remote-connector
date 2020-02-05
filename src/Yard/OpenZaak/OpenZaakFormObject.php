<?php

namespace Yard\OpenZaak;

class OpenZaakFormObject
{
    protected $fields;

    protected $entry;

    public function __construct(array $fields = [], array $entry = [])
    {
        $this->fields = $fields;
        $this->entry  = $entry;
    }

    public static function make(array $fields = [], array $entry = [])
    {
        return new static($fields, $entry);
    }

    public function buildFields(): array
    {
        $formEntries = [];
        $settings    = AttributesManager::make()->find('edwin');
        dd($settings);

        foreach ($this->fields as $field) {
            if (!isset($field['casePropertyName'])) {
                continue;
            }
            dd($field, rgar($this->entry, (string) $field['id']));
            $formEntries = array_merge($formEntries, [
                trim(rgar($this->entry, (string) $field['id'])) => trim($field['casePropertyName'])
            ]);
        }
        return $formEntries;
    }

    public function toJson()
    {
        return dd(json_encode($this->buildFields()));
    }
}
