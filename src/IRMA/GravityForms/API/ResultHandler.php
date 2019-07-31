<?php

namespace IRMA\WP\GravityForms\API;

use GFAPI;
use GFFormsModel;
use IRMA\WP\Client\IRMAClient;
use IRMA\WP\Settings\SettingsManager;

class ResultHandler
{

	/**
	 * @var IRMAClient
	 */
	private $client;

	public function __construct(IRMAClient $client)
	{
		$this->client = $client;
	}

	public function handle()
	{
		$formId = $_POST['formId'];
		$form = GFAPI::get_form($formId);

		$token = $_POST['token'];

		$attributes = $this->client->setToken($token)->getResult();

		$result = [];

		foreach ($form['fields'] as $field) {
			if ($field['type'] != 'IRMA-attribute' || !in_array($field['irmaAttribute'], $attributes->getIds())) {
				continue;
			}

			$result[] = [
				'input' => 'input_' . $formId . '_' . $field['id'],
				'label' => $field['label'],
				'attribute' => $field['irmaAttribute'],
				'value' => $attributes[$field['irmaAttribute']]->getValue()
			];
		}

		set_transient('irma_result_' . $token, $result, WEEK_IN_SECONDS);

		return $result;
	}
}
