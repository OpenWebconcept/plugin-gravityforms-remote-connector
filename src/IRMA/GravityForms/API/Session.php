<?php

namespace IRMA\WP\GravityForms\API;

use GFAPI;
use WP_REST_Request;
use WP_REST_Response;

class Session
{
	/**
	 * Create an IRMA session for a specific form.
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function handle(WP_REST_Request $request)
	{
		$form = GFAPI::get_form($request->get_param('id'));

		if (!$form) {
			return new WP_REST_Response([
				'error' => 'Could not find form.'
			], 400);
		}

		$attributes  = [];

		foreach ($form['fields'] as $field) {
			if ($field['type'] != 'IRMA-attribute') {
				continue;
			}

			$attributes[] = [
				'label' => $field['label'],
				'attributes' => [
					$field['irmaAttribute']
				]
			];
		}

		$request = wp_remote_post('https://metrics.privacybydesign.foundation/irmaserver/session', [
			'method' => 'POST',
			'headers' => [
				'Content-Type' => 'application/json'
			],
			'body' => json_encode([
				'type' => 'disclosing',
				'content' => $attributes
			])
		]);

		return new WP_REST_Response(json_decode(wp_remote_retrieve_body($request)));
	}
}
