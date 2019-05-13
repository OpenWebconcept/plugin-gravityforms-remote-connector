<?php

namespace IRMA\WP\Client;

use IRMA\WP\AttributeCollection;
use IRMA\WP\Attribute;

class IRMAClient
{

	/**
	 * @var string|null
	 */
	private $endpoint;

	/**
	 * @var string|null
	 */
	private $token = null;

	/**
	 * @param string $endpoint
	 */
	public function __construct($endpoint = null)
	{
		$this->endpoint = rtrim($endpoint, '/');
	}

	/**
	 * @param array $attributes
	 * @return array
	 */
	public function getSession(array $attributes): array
	{
		return $this->post('session', [
			'type' => 'disclosing',
			'content' => $attributes
		]);
	}

	/**
	 * @return AttributeCollection
	 */
	public function getResult(): AttributeCollection
	{
		$collection = AttributeCollection::make();

		foreach ($this->get("session/$this->token/result")['disclosed'] ?? [] as $attr) {
			$collection->add(new Attribute($attr['id'], $attr['rawvalue'], $attr['value'], $attr['status']));
		}

		return $collection;
	}

	/**
	 * @param string $value
	 * @return IRMAClient
	 */
	public function setEndpoint($value): IRMAClient
	{
		$this->endpoint = $value;

		return $this;
	}

	/**
	 * @param string $value
	 * @return IRMAClient
	 */
	public function setToken($value): IRMAClient
	{
		$this->token = $value;

		return $this;
	}

	/**
	 * @param string $endpoint
	 * @param array $payload
	 * @return array
	 */
	private function post(string $endpoint, array $payload = []): array
	{
		$response = wp_remote_post($this->endpoint . '/' . $endpoint, [
			'headers' => [
				'Content-Type' => 'application/json'
			],
			'body' => json_encode($payload)
		]);

		return json_decode(wp_remote_retrieve_body($response), true);
	}

	/**
	 * @param string $endpoint
	 * @return array
	 */
	private function get(string $endpoint): array
	{
		$response = wp_remote_get($this->endpoint . '/' . $endpoint);

		return json_decode(wp_remote_retrieve_body($response), true);
	}
}
