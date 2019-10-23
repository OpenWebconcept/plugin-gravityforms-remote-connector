<?php

namespace IRMA\WP\Settings;

use IRMA\WP\Foundation\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        add_action('wp_ajax_irma_store_settings', [new StoreSettingsHandler(), 'handle']);
        add_action('wp_ajax_irma_store_settings_decos', [new StoreSettingsHandler(), 'handleDecosSettings']);
        add_action('wp_ajax_irma_get_settings_decos', [new StoreSettingsHandler(), 'getDecosSettings']);
        add_action('wp_ajax_irma_delete_setting_decos', [new StoreSettingsHandler(), 'deleteDecosSetting']);

        add_action('admin_menu', function () {
            add_menu_page('irma', 'IRMA', 'manage_options', 'irma', function () {
                $settings = new SettingsManager();
                $irmaLogoUrl = $this->plugin->resourceUrl('img/irma_logo.png');
                $deleteIcon = $this->plugin->resourceUrl('img/delete.png');
                require __DIR__.'/resources/settings-page.php';
            }, $this->plugin->resourceUrl('img/irma_icon.png'), 300);
        });
    }
}
