<?php

namespace Yard\IRMA\GravityForms;

use GFAddOn;
use GFForms;
use GF_Fields;
use Yard\IRMA\Client\IRMAClient;
use Yard\IRMA\Foundation\ServiceProvider;
use Yard\IRMA\GravityForms\Fields\IrmaAttributeField;
use Yard\IRMA\GravityForms\Fields\IrmaHeaderField;
use Yard\IRMA\GravityForms\Fields\IrmaLaunchQR;
use Yard\IRMA\GravityForms\OpenZaak\OpenZaakAddon;
use Yard\IRMA\GravityForms\OpenZaak\Settings;
use Yard\IRMA\Settings\SettingsManager;

class GravityFormsServiceProvider extends ServiceProvider
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
        $this->registerFilters();
        $this->registerRestRoutes();
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

        add_action('gform_loaded', [$this, 'loadIRMAAddon'], 5);
        add_action('gform_loaded', [$this, 'loadOpenZaak'], 5);
        add_action('gform_enqueue_scripts', [$this, 'enqueueScripts'], 10, 2);
        add_action('gform_field_standard_settings', [$this, 'addCustomAttributeToField'], 10, 2);
        add_action('gform_editor_js', [$this, 'addCustomAttributeToFieldScript'], 11, 2);
    }

    /**
     * Load IRMA fields.
     *
     * @return void
     */
    public static function loadIRMAAddon(): void
    {
        GF_Fields::register(new IrmaAttributeField());
        GF_Fields::register(new IrmaLaunchQR());
        GF_Fields::register(new IrmaHeaderField());

        GFAddOn::register('Yard\IRMA\GravityForms\IrmaAddOn');
    }

    /**
     * Load OpenZaak fields & settings.
     *
     * @return void
     */
    public function loadOpenZaak(): void
    {
        GFAddOn::register('Yard\IRMA\GravityForms\OpenZaak\OpenZaakAddon');

        OpenZaakAddon::get_instance();
    }

    /**
     * Add filters.
     *
     * @return void
     */
    public function registerFilters(): void
    {
        add_filter('gform_after_submission', [new Filters\DisableEntryCreation(), 'apply'], 10, 3);
    }

    /**
     * Register routes for the REST API.
     *
     * @return void
     */
    public function registerRestRoutes(): void
    {
        $client = new IRMAClient($this->settings->getEndpointUrl(), $this->settings->getEndpointToken());

        add_action('rest_api_init', function () use ($client) {
            register_rest_route('irma/v1', '/gf/handle', [
                'methods'  => 'POST',
                'callback' => [new API\ResultHandler($client), 'handle'],
            ]);
        });

        add_action('rest_api_init', function () use ($client) {
            register_rest_route('irma/v1', '/gf/session', [
                'methods'  => 'GET',
                'callback' => [new API\Session($client), 'handle'],
            ]);
        });
    }

    /**
     * @param array $form
     * @param bool  $is_ajax
     */
    public function enqueueScripts($form, $is_ajax)
    {
        wp_register_script('irma-gf-js', $this->plugin->resourceUrl('irma-gf.js'), ['jquery'], false, true);

        wp_localize_script('irma-gf-js', 'irma_gf', [
            'handle_url'  => get_rest_url(null, 'irma/v1/gf/handle'),
            'session_url' => get_rest_url(null, 'irma/v1/gf/session'),
        ]);

        wp_enqueue_script('irma-gf-js');
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
            require __DIR__ . '/views/select-attribute.php';
        }
    }

    /**
     * Add script to facilitate custom attribute.
     *
     * @return void
     */
    public function addCustomAttributeToFieldScript(): void
    {
        require __DIR__ . '/views/select-attribute.php';
    }
}
