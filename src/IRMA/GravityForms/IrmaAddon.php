<?php

namespace IRMA\WP\GravityForms;

use GFAddOn;

class IrmaAddOn extends GFAddOn
{
	protected $_version = IRMA_WP_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'irma-addon';
	protected $_path = 'irma-wp/plugin.php';
	protected $_full_path = __FILE__;
	protected $_title = 'GravityForms IRMA Add-On';
	protected $_short_title = 'IRMA Add-On';

	private static $_instance = null;

	public static function get_instance()
	{
		if (self::$_instance == null) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function init_admin()
	{
		parent::init_admin();
		// add_filter('gform_tooltips', [$this, 'tooltips']);
		add_action('gform_field_standard_settings', [$this, 'field_appearance_settings'], 10, 2);
		add_action('gform_editor_js', [$this, 'editor_script']);
	}

	/**
	 * Add the custom setting to the appearance tab.
	 *
	 * @param int $position The position the settings should be located at.
	 * @param int $formId The ID of the form currently being edited.
	 */
	public function field_appearance_settings($position, $formId)
	{
		if ($position == 350) {
			require __DIR__ . '/resources/irma-form-settings.php';
		}
	}


	/**
	 * Scripting necessary for the form editor.
	 *
	 * @return string
	 */
	public function editor_script()
	{
		require __DIR__ . '/resources/editor-script.php';
	}

	/**
	 * Fields to be shown on the settings page.
	 *
	 * @return array
	 */
	public function form_settings_fields($form)
	{
		return [
			[
				'title'  => esc_html__('IRMA Form Settings', 'irma-wp'),
				'fields' => [
					[
						'label'             => esc_html__('IRMA-server Endpoint', 'irma-wp'),
						'type'              => 'text',
						'name'              => 'endpointIRMA',
						'tooltip'           => esc_html__('URL to the IRMA-server endpoint', 'irma-wp'),
						'class'             => 'medium',
						'feedback_callback' => array($this, 'validateEndpoint'),
					],
				],
			]
		];
	}

	/**
	 * Validate if the configured endpoint is a valid IRMA server.
	 *
	 * @param string $value
	 * @return bool
	 */
	public function validateEndpoint($value)
	{
		$request = wp_remote_post($value, [
			'method' => 'GET',
		]);

		if (is_wp_error($request)) {
			return false;
		}

		return $request['response']['code'] == 200;
	}
}
