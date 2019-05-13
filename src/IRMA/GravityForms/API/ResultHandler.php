<?php

namespace IRMA\WP\GravityForms\API;

class ResultHandler
{
	public function handle()
	{
		$formId = $_POST['formId'];
		$token = $_POST['token'];

		$meta_value = \GFFormsModel::get_form_meta($formId)['irma-settings-addon']['endpointIRMA'];

		$endpoint = $meta_value . "/irmaserver/session/$token/result";

		$request = wp_remote_post($endpoint, [
			'method' => 'GET',
		]);

		$response = json_decode(wp_remote_retrieve_body($request));
		$disclosed = isset($response->disclosed) ? $response->disclosed : [];

		$attributes = [];

		foreach ($disclosed as $attribute) {
			$attributes[$attribute->id] = $attribute->rawvalue;
		}

		$form = \GFAPI::get_form($formId);

		$result = [];

		foreach ($form['fields'] as $field) {
			if ($field['type'] != 'IRMA-attribute' || !in_array($field['irmaAttribute'], array_keys($attributes))) {
				continue;
			}

			$result[] = [
				'input' => 'input_' . $formId . '_' . $field['id'],
				'label' => $field['label'],
				'attribute' => $field['irmaAttribute'],
				'value' => $attributes[$field['irmaAttribute']]
			];
		}

		set_transient('irma_result_' . $token, $result, WEEK_IN_SECONDS);

		return $result;
	}
}
