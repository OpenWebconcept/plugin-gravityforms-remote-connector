<?php

namespace Yard\IRMA\Settings;

class StoreSettingsHandler
{
    /**
     * Settings that can be filled.
     *
     * @var array
     */
    private $fillable = [
        'irma_server_endpoint_url',
        'irma_server_endpoint_token',
        'irma_wp_rsin',
        'irma_wp_bsn_attribute',
        'irma_wp_decos_attribute_name',
        'irma_wp_decos_attribute_value',
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
        check_ajax_referer('irma_store_settings', 'security');

        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => 'Permission denied.',
            ], 403);
        }

        parse_str($_POST['data'] ?? '', $data);

        $settings = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $settings[$key] = $value;
            }
        }

        update_option('irma_settings', $settings);

        return wp_send_json_success([
            'message' => 'IRMA configuration has been updated!',
        ]);
    }

    /**
     * get Decos settings.
     *
     * @return array
     */
    public function getDecosSettings()
    {
        $settingsManager = new SettingsManager();

        $settings = $settingsManager->getAttributeDecos();

        $settings = array_values($settings);

        return wp_send_json_success([
            'decosAttributes' => $settings,
        ]);
    }

    public function deleteDecosSetting()
    {
        $settingsManager = new SettingsManager();

        $settings = $settingsManager->getAttributeDecos();

        foreach ($settings as $i => $setting) {
            if ($setting['caseProperty'] == $_POST['data']) {
                unset($settings[$i]);
            }
        }

        update_option('irma_settings_decos', $settings);

        $this->getDecosSettings();
    }

    /**
     * Handle the AJAX request.
     *
     * @return string
     */
    public function handleDecosSettings()
    {
        check_ajax_referer('irma_store_settings_decos', 'security');

        if (!current_user_can('manage_options')) {
            return wp_send_json_error([
                'message' => 'Permission denied.',
            ], 403);
        }

        parse_str($_POST['data'] ?? '', $data);

        $newSetting = ['caseProperty' => $data['irma_wp_decos_attribute_name'], 'casePropertyValue' => $data['irma_wp_decos_attribute_value']];

        $settingsManager = new SettingsManager();

        $settings = $settingsManager->getAttributeDecos();

        array_push($settings, $newSetting);

        update_option('irma_settings_decos', $settings);

        $settings = array_values($settings);

        return wp_send_json_success([
            'message'         => 'IRMA Decos configuration has been updated!',
            'decosAttributes' => $settings,
        ]);
    }
}
