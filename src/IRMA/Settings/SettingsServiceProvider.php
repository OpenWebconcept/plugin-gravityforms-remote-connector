<?php

namespace Yard\IRMA\Settings;

use Yard\IRMA\Foundation\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        add_action('wp_ajax_openzaak_store_settings', [new StoreSettingsHandler(), 'handle']);
        // add_action('wp_ajax_openzaak_store_settings_store_settings_decos', [new StoreSettingsHandler(), 'handleIrmaSettings']);
        // add_action('wp_ajax_irma_get_settings_decos', [new StoreSettingsHandler(), 'getDecosSettings']);
        // add_action('wp_ajax_irma_delete_setting_decos', [new StoreSettingsHandler(), 'deleteDecosSetting']);
    }
}
