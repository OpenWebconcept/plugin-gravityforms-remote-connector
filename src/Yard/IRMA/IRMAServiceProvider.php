<?php

namespace Yard\IRMA;

use GFAddOn;
use GFForms;
use GF_Fields;
use UnexpectedValueException;
use Yard\Foundation\ServiceProvider;
use Yard\IRMA\Client\IRMAClient;
use Yard\IRMA\Filters\DisableEntryCreation;
use Yard\IRMA\GravityForms\Fields\IrmaAttributeField;
use Yard\IRMA\GravityForms\Fields\IrmaHeaderField;

class IRMAServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities for GravityForms.
     */
    public function register()
    {
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
        add_action('gform_enqueue_scripts', [$this, 'enqueueScripts'], 10, 2);
    }

    /**
     * Load IRMA fields.
     *
     * @return void
     */
    public static function loadIRMAAddon(): void
    {
        GF_Fields::register(new IrmaAttributeField());
        GF_Fields::register(new IrmaHeaderField());

        GFAddOn::register('Yard\IRMA\IRMAAddOn');
    }

    /**
     * Add filters.
     *
     * @return void
     */
    public function registerFilters(): void
    {
        add_filter('gform_after_submission', [new DisableEntryCreation(), 'apply'], 10, 3);
    }

    /**
     * Register routes for the REST API.
     *
     * @return void
     */
    public function registerRestRoutes(): void
    {
        if (is_admin()) {
            return;
        }
        $settings = SettingsManager::make();
        try {
            $client   = new IRMAClient($settings->getEndpointUrl(), $settings->getEndpointToken());

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
        } catch (UnexpectedValueException $e) {
            require_once __DIR__ . '/views/setting-error.php';
            exit;
        }
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
}
