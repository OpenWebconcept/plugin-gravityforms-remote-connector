<?php

namespace IRMA\WP\Settings;

class StoreSettingsHandler
{

	/**
	 * Settings that can be filled.
	 *
	 * @var array
	 */
	private $fillable = [
		'irma_server_endpoint_url'
	];

	/**
	 * Handle the AJAX request.
	 *
	 * @return string
	 */
	public function handle()
	{
		check_ajax_referer('irma_store_settings', 'security');

		if (!current_user_can('manage_options')) {
			return wp_send_json_error([
				'message' => 'Permission denied.'
			], 403);
		}

		parse_str($_POST['data'] ?? '', $data);

		$settings = [];

		foreach ($data as $key => $value) {
			if (in_array($key, $this->fillable)) {
				$settings[$key] = $value;
			}
		}

		update_option('irma_settings', $settings);

		return wp_send_json_success([
			'message' => 'IRMA configuration has been updated!'
		]);
	}
}
