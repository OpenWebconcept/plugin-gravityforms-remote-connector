<?php

namespace Yard\OpenZaak\Settings;

use Yard\OpenZaak\KeyValuePair;
use Yard\OpenZaak\OpenZaakSettingsManager;

class ListField
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
        $this->data = OpenZaakSettingsManager::make()->get('attributes');
    }

    public function render()
    {
        echo '<ul>';
        foreach ($this->data as $key => $data) {
            $data = KeyValuePair::make($data);
            echo '<li>'. $data->key() .' -> '. $data->value() .' (X) </li>';
        }
        echo '</ul>';
    }
}
