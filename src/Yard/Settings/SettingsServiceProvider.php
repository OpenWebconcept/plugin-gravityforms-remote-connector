<?php

namespace Yard\Settings;

use Yard\Foundation\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        add_action('wp_ajax_openzaak_store_settings', [new StoreSettingsHandler(), 'handle']);
    }
}
