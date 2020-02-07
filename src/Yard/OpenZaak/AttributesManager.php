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
     * @param array $default
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

    /**
     * Find a specific value by key.
     *
     * @param string $value
     * @param array $default
     * @param string $key
     *
     * @return string|array
     */
    public function findValue(string $value, $default = [], $key = '')
    {
        $all = $this->get('attributes', $default);
        $id  = array_search($value, array_column($all, 'name'));

        if (empty($key)) {
            return $all[$id] ?? null;
        }

        return ($all[$id][$key] ?? $all[$id]);
    }

    /**
	 * @inheritDo
	 */
    public function save(array $data): bool
    {
        if (!isset($data['attributes'])) {
            $dataTmp               = [];
            $dataTmp['attributes'] = $data;
            $data                  = $dataTmp;
        }

        return update_option($this->key, $data);
    }
}
