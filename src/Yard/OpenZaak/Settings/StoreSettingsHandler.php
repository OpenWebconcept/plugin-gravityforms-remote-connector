<?php

namespace Yard\OpenZaak\Settings;

use Yard\OpenZaak\SettingsManager;

class StoreSettingsHandler
{
    /**
     * Prefix of the metadata.
     *
     * @var string
     */
    protected $prefix = 'openzaak_attribute_';

    /**
     * Handle the AJAX request.
     *
     * @return string
     */
    public function handle()
    {
        check_ajax_referer('openzaak_store_settings', 'security');

        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => 'Permission denied.',
            ], 403);
        }

        parse_str($_POST['data'] ?? [], $data);

        $settings                 = SettingsManager::make()->all();
        $settings['attributes'][] = $this->removePrefix($data);

        if (SettingsManager::make()->save($settings)) {
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
     * @return array[]
     */
    private function removePrefix(array $data): array
    {
        return array_combine(
            array_map(function ($k) {
                return str_replace($this->prefix, '', $k);
            }, array_keys($data)),
            $data
        );
    }
}
