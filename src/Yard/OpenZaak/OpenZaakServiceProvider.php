<?php

namespace Yard\OpenZaak;

use GFAddOn;
use GFForms;
use Yard\Foundation\ServiceProvider;
use Yard\OpenZaak\Settings\AttributeStorageHandler;

class OpenZaakServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities for GravityForms.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerActions();
    }

    /**
     * Register all the actions.
     *
     * @return void
     */
    public function registerActions(): void
    {
        if (! method_exists(GFForms::class, 'include_addon_framework')) {
            return;
        }

        GFForms::include_addon_framework();

        add_action('wp_ajax_openzaak_store_attribute', [new AttributeStorageHandler(), 'store']);
        add_action('wp_ajax_openzaak_remove_attribute', [new AttributeStorageHandler(), 'remove']);

        add_action('gform_loaded', [$this, 'loadOpenZaak'], 5);
        add_action('gform_field_standard_settings', [$this, 'addCustomAttributeToField'], 10, 2);
        add_action('gform_editor_js', [$this, 'addCustomAttributeToFieldScript'], 11, 2);
    }

    /**
     * Load OpenZaak fields & settings.
     *
     * @return void
     */
    public function loadOpenZaak(): void
    {
        GFAddOn::register(\Yard\OpenZaak\Addon::class);

        Addon::get_instance();
    }

    /**
     * Add Custom attribute to fields.
     *
     * @param int $position
     * @param int $form_id
     *
     * @return void
     */
    public function addCustomAttributeToField(int $position, int $form_id): void
    {
        if (0 == $position) {
            $attributes = AttributesManager::make();
            require __DIR__ . '/GravityForms/views/select-attribute.php';
        }
    }

    /**
     * Add script to facilitate custom attribute.
     *
     * @return void
     */
    public function addCustomAttributeToFieldScript(): void
    {
        require __DIR__ . '/GravityForms/views/select-attribute-script.php';
    }
}
