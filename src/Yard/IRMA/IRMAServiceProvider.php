<?php

namespace Yard\IRMA;

use GFAddOn;
use GFForms;
use GF_Fields;
use UnexpectedValueException;
use WP_REST_Server;
use Yard\Foundation\ServiceProvider;
use Yard\IRMA\Client\IRMAClient;
use Yard\IRMA\Filters\DisableEntryCreation;
use Yard\IRMA\GravityForms\Fields\IrmaAttributeField;
use Yard\IRMA\GravityForms\Fields\IrmaHeaderField;

class IRMAServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities for GravityForms.
     *
     * @return void
     */
    public function register(): void
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
        add_action('init', function () {
            add_filter('config_expander_admin_defaults', function ($defaults) {
                $defaults['DISABLE_REST_API'] = false;
                return $defaults;
            });

            add_filter('config_expander_rest_endpoints_whitelist', function ($endpoints_whitelist) {

                //remove default root endpoint
                unset($endpoints_whitelist['wp/v2']);

                $endpoints_whitelist['/irma/v1/gf/handle'] = [
                    'endpoint_stub' => '/irma/v1/gf/handle',
                    'methods'       => ['POST']
                ];
                $endpoints_whitelist['/irma/v1/gf/session'] = [
                    'endpoint_stub' => '/irma/v1/gf/session',
                    'methods'       => ['GET']
                ];

                return $endpoints_whitelist;
            }, 10, 1);
        });
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

        $settings = IRMASettingsManager::make();
        if (empty($settings->getEndpointUrl())) {
            return;
        }

        try {
            $client   = new IRMAClient($settings->getEndpointUrl(), $settings->getEndpointToken());

            add_action('rest_api_init', function () use ($client) {
                register_rest_route('irma/v1', '/gf/handle', [
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => [new API\ResultHandler($client), 'handle'],
                ]);

                register_rest_route('irma/v1', '/gf/session', [
                    'methods'  => WP_REST_Server::READABLE,
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
     * @return void
     */
    public function enqueueScripts($form, $is_ajax): void
    {
        wp_register_script('irma-gf-js', $this->plugin->resourceUrl('irma-gf.js'), ['jquery'], date('ymd'), true);

        wp_localize_script('irma-gf-js', 'irma_gf', [
            'handle_url'  => get_rest_url(null, 'irma/v1/gf/handle'),
            'session_url' => get_rest_url(null, 'irma/v1/gf/session'),
        ]);

        wp_enqueue_script('irma-gf-js');
    }
}
