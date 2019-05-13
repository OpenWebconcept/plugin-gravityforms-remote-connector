<?php

namespace IRMA\WP\GravityForms\API;

use GFAPI;
use GFFormsModel;
use WP_REST_Request;
use WP_REST_Response;
use IRMA\WP\Client\IRMAClient;
use IRMA\WP\AttributeCollection;

class Session
{

	/**
	 * @var IRMAClient
	 */
	private $client;

	public function __construct(IRMAClient $client)
	{
		$this->client = $client;
	}

	/**
	 * Create an IRMA session for a specific form.
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function handle(WP_REST_Request $request)
	{
		$formId = $request->get_param('id');
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

			$attributes[] = AttributeCollection::make()
				->setLabel($field['label'])
				->addAttribute($field['irmaAttribute']);
		}

		return new WP_REST_Response($this->client->setEndpoint($this->getEndpoint($formId))->getSession($attributes));
	}

	/**
	 * @param integer|null $formId
	 * @return string
	 */
	public function getEndpoint(int $formId)
	{
		return GFFormsModel::get_form_meta($formId)['irma-addon']['endpointIRMA'] ?? null;
	}
}
