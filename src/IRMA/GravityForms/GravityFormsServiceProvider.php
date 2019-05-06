<?php

namespace IRMA\WP\GravityForms;

use GF_Fields;
use GFAddOn;
use GFForms;
use IRMA\WP\Foundation\ServiceProvider;

class GravityFormsServiceProvider extends ServiceProvider
{

	public function register()
	{
		GF_Fields::register(new IrmaAttributeField);
		GF_Fields::register(new IrmaLaunchQR);

		add_action('gform_loaded', [$this, 'onGravityFormsLoaded'], 5);

		$this->registerRestRoutes();

		add_action('gform_enqueue_scripts', [$this, 'enqueueScripts'], 10, 2);
	}

	/**
	 * Register routes for the REST API.
	 *
	 * @return void
	 */
	public function registerRestRoutes()
	{
		add_action('rest_api_init', function () {
			register_rest_route('irma/v1', '/gf/handle', [
				'methods' => 'POST',
				'callback' => [new API\ResultHandler, 'handle'],
			]);
		});

		add_action('rest_api_init', function () {
			register_rest_route('irma/v1', '/gf/session', [
				'methods' => 'GET',
				'callback' => [new API\Session, 'handle'],
			]);
		});
	}

	/**
	 * Load the add-on once GravityForms has been loaded.
	 *
	 * @return void
	 */
	public function onGravityFormsLoaded()
	{
		GFForms::include_addon_framework();
		GFAddOn::register(IrmaAddOn::class);
	}

	/**
	 * @param array $form
	 * @param bool $is_ajax
	 * @return void
	 */
	public function enqueueScripts($form, $is_ajax)
	{
		wp_register_script('irma-gf-js', $this->plugin->resourceUrl('irma-gf.js'), ['jquery']);

		wp_localize_script('irma-gf-js', 'irma_gf', [
			'handle_url' => get_rest_url(null, 'irma/v1/gf/handle'),
			'session_url' => get_rest_url(null, 'irma/v1/gf/session')
		]);

		wp_enqueue_script('irma-gf-js');
	}
}
