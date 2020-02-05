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
    protected $key = 'gravityformsaddon_openzaak-addon_attributes';

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

        return $all['attributes'];
    }

    public function find($value, $default = [], $key = '')
    {
        $all = $this->get('attributes', $default);
        $id  = array_search($value, array_column($all, 'name'));

        if (empty($key)) {
            return $all[$id] ?? null;
        }

        return ($all[$id][$key] ?? $all[$id]);
    }

    public function save($data): bool
    {
        if (!isset($data['attributes'])) {
            $dataTmp               = [];
            $dataTmp['attributes'] = $data;
            $data                  = $dataTmp;
        }

        return update_option($this->key, $data);
    }
}
