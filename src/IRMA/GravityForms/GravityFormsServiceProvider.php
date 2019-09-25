<?php

namespace IRMA\WP\GravityForms;

use GF_Fields;
use GFAddOn;
use GFForms;
use IRMA\WP\Foundation\ServiceProvider;
use IRMA\WP\Client\IRMAClient;
use IRMA\WP\Foundation\Plugin;
use IRMA\WP\Settings\SettingsManager;

class GravityFormsServiceProvider extends ServiceProvider
{
    /**
     * @var SettingsManager
     */
    private $settings;

    public function __construct(Plugin $plugin)
    {
        parent::__construct($plugin);

        $this->settings = new SettingsManager();
    }

    /**
     * Register all necessities for GravityForms.
     */
    public function register()
    {
        GF_Fields::register(new IrmaAttributeField());
        GF_Fields::register(new IrmaLaunchQR());
        GF_Fields::register(new IrmaHeaderField());

        $this->registerActions();
        $this->registerFilters();

        $this->registerRestRoutes();
    }

    public function registerActions()
    {
        add_action('gform_loaded', [$this, 'onGravityFormsLoaded'], 5);
        add_action('gform_enqueue_scripts', [$this, 'enqueueScripts'], 10, 2);
    }

    public function registerFilters()
    {
        add_filter('gform_after_submission', [new Filters\DisableEntryCreation(), 'apply'], 10, 3);
    }

    /**
     * Register routes for the REST API.
     */
    public function registerRestRoutes()
    {
        $client = new IRMAClient($this->settings->getEndpointUrl());

        add_action('rest_api_init', function () use ($client) {
            register_rest_route('irma/v1', '/gf/handle', [
                'methods' => 'POST',
                'callback' => [new API\ResultHandler($client), 'handle'],
            ]);
        });

        add_action('rest_api_init', function () use ($client) {
            register_rest_route('irma/v1', '/gf/session', [
                'methods' => 'GET',
                'callback' => [new API\Session($client), 'handle'],
            ]);
        });
    }

    /**
     * Load the add-on once GravityForms has been loaded.
     */
    public function onGravityFormsLoaded()
    {
        GFForms::include_addon_framework();
        GFAddOn::register(IrmaAddOn::class);
    }

    /**
     * @param array $form
     * @param bool  $is_ajax
     */
    public function enqueueScripts($form, $is_ajax)
    {
        wp_register_script('irma-gf-js', $this->plugin->resourceUrl('irma-gf.js'), ['jquery'], false, true);

        wp_localize_script('irma-gf-js', 'irma_gf', [
            'handle_url' => get_rest_url(null, 'irma/v1/gf/handle'),
            'session_url' => get_rest_url(null, 'irma/v1/gf/session'),
        ]);

        wp_enqueue_script('irma-gf-js');
    }
}
