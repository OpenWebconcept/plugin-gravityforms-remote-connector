<?php

namespace Yard\OpenZaak\Settings\Fields;

use Yard\OpenZaak\KeyValuePair;
use Yard\OpenZaak\SettingsManager;

class ListField
{
    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
        $this->data = SettingsManager::make()->get('attributes');
    }

    public function render()
    {
        echo '<table>';
        foreach ($this->data as $key => $data) {
            echo '<tr>';
            $data = KeyValuePair::make($data);
            echo '<td><em>'. $data->key() .'</td><td> -> </td><td><em>'. $data->value() .'</em></td><td> &nbsp; &nbsp; &nbsp;(<a href="#">verwijder</a>)</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
