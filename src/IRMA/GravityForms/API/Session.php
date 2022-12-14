<?php

namespace IRMA\WP\GravityForms\API;

use GFAPI;
use GFFormsModel;
use WP_REST_Request;
use WP_REST_Response;
use IRMA\WP\Client\IRMAClient;
use IRMA\WP\Client\SessionAttributeCollection;

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

			$attributes[] = SessionAttributeCollection::make()
				->setLabel($field['label'])
				->add($field['irmaAttribute']);
		}

		return new WP_REST_Response($this->client->getSession($attributes));
	}
}
