<?php

namespace IRMA\WP\Client;

class IRMAClient
{

	/**
	 * @var string|null
	 */
	private $endpoint;

	/**
	 * @param string $endpoint
	 */
	public function __construct($endpoint = null)
	{
		$this->endpoint = rtrim($endpoint, '/');
	}

	/**
	 * @param array $attributes
	 * @return \stdClass
	 */
	public function getSession(array $attributes)
	{
		$response = wp_remote_post($this->endpoint . '/session', [
			'method' => 'POST',
			'headers' => [
				'Content-Type' => 'application/json'
			],
			'body' => json_encode([
				'type' => 'disclosing',
				'content' => $attributes
			])
		]);

		return json_decode(wp_remote_retrieve_body($response));
	}

	/**
	 * @param string $value
	 * @return IRMAClient
	 */
	public function setEndpoint($value)
	{
		$this->endpoint = $value;

		return $this;
	}
}
