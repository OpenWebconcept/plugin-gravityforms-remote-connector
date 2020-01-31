<?php

namespace Yard\OpenZaak\Settings;

class ListField
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
        $this->data = get_option('openzaak_settings', []);
    }

    public function render()
    {
        echo '<ul>';
        foreach ($this->data as $key => $data) {
            echo '<li>'. $data->key() .' -> '. $data->value() .' (X) </li>';
        }
        echo '</ul>';
    }
}
