<?php

namespace Yard\OpenZaak\Settings;

use Yard\OpenZaak\AttributesManager;

class AttributeStorageHandler
{
    /**
     * Handle the attribute store AJAX request.
     *
     * @return string
     */
    public function store()
    {
        check_ajax_referer('openzaak_store_attribute', 'security');

        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => 'Permission denied.',
            ], 403);
        }

        parse_str($_POST['data'] ?? [], $data);

        $settings                 = AttributesManager::make()->all();
        $settings[]               = $this->removePrefix($data);

        if (AttributesManager::make()->save($settings)) {
            return wp_send_json_success([
                'message' => 'Openzaak configuration has been updated!',
            ]);
        }

        return wp_send_json_error([
            'message' => 'Openzaak configuration has NOT been updated!',
        ]);
    }

    /**
     * Handle the attribute removal AJAX request.
     *
     * @return string
     */
    public function remove()
    {
        check_ajax_referer('openzaak_remove_attribute', 'security');

        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => 'Permission denied.',
            ], 403);
        }

        $attributeKey             = $_POST['data']['attribute'] ?? '';
        $settings                 = AttributesManager::make()->all();
        $key                      = array_search($attributeKey, array_column($settings, 'name'));
        if (false === $key) {
            return wp_send_json_error([
                'message' => 'Openzaak configuration has NOT been updated!',
            ]);
        }

        unset($settings[$key]);
        sort($settings);

        if (AttributesManager::make()->save($settings)) {
            return wp_send_json_success([
                'message' => 'Openzaak configuration has been updated!',
            ]);
        }

        return wp_send_json_error([
            'message' => 'Openzaak configuration has NOT been updated!',
        ]);
    }

    /**
     * Remove any prefixed data for readability.
     *
     * @param array[] $data
     *
     * @return array
     */
    private function removePrefix(array $data): array
    {
        $prefix  = 'openzaak_attribute_';
        $newData = array_combine(
            array_map(function ($k) use ($prefix) {
                return str_replace($prefix, '', $k);
            }, array_keys($data)),
            $data
        );
        if (false === $newData) {
            return $data;
        }

        return $newData;
    }
}
