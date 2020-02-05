<?php

namespace Yard\OpenZaak;

use Yard\Foundation\SettingsManager as BaseSettingsManager;

class AttributesManager extends BaseSettingsManager
{
    /**
     * OpenZaak settings key.
     *
     * @var string
     */
    protected $key = 'openzaak_settings';

    /**
     * Get the attributes.
     *
     * @return array[]
     */
    public function all($default = []): array
    {
        $all = get_option($this->key, $default);
        if (!isset($all['attributes'])) {
            $all['attributes'] = [];
        }

        return $all;
    }

    public function find($key, $default = [])
    {
        $all = $this->get('attributes', $default);

        $id = array_search($key, array_column($all, 'name'));
        return $all[$id] ?? null;
    }
}
