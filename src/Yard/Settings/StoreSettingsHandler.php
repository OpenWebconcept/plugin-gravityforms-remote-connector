<?php

namespace Yard\IRMA\Settings;

use Yard\IRMA\GravityForms\OpenZaak\KeyValuePair;

class StoreSettingsHandler
{
    /**
     * Settings that can be filled.
     *
     * @var array
     */
    private $fillable = [
        'irma_wp_rsin',
        'irma_wp_bsn_attribute',
        'irma_wp_irma_attribute_name',
        'irma_wp_irma_attribute_value',
        'createCaseURL',
        'createCaseObjectURL',
        'createCasePropertyURL',
    ];

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

        parse_str($_POST['data'] ?? '', $data);

        $object = KeyValuePair::make($data);
        $data   = [];

        $settings   = get_option('openzaak_settings', []);
        if (!empty($settings)) {
            $data = $settings;
        }
        $data[] = $object;

        if (update_option('openzaak_settings', $data)) {
            return wp_send_json_success([
                'message' => 'Openzaak configuration has been updated!',
            ]);
        }

        return wp_send_json_error([
            'message' => 'Openzaak configuration has NOT been updated!',
        ]);
    }
}
