<?php

namespace Yard\OpenZaak;

use GFAddOn;
use GFForms;
use Yard\Foundation\ServiceProvider;
use Yard\Settings\SettingsManager;

class OpenZaakServiceProvider extends ServiceProvider
{
    /**
     * @var SettingsManager
     */
    private $settings;

    /**
     * Register all necessities for GravityForms.
     */
    public function register()
    {
        $this->settings = new SettingsManager();

        $this->registerActions();
    }

    /**
     * Register all the actions.
     *
     * @return void
     */
    public function registerActions(): void
    {
        if (! method_exists('\GFForms', 'include_addon_framework')) {
            return;
        }

        GFForms::include_addon_framework();

        add_action('gform_loaded', [$this, 'loadOpenZaak'], 5);
    }

    /**
     * Load OpenZaak fields & settings.
     *
     * @return void
     */
    public function loadOpenZaak(): void
    {
        GFAddOn::register('Yard\OpenZaak\OpenZaakAddon');

        OpenZaakAddon::get_instance();
    }
}
