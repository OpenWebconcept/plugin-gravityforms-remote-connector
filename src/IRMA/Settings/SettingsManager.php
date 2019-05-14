<?php

namespace IRMA\WP\Settings;

class SettingsManager
{

	/**
	 * @var array
	 */
	private $settings;

	public function __construct()
	{
		$this->settings = get_option('irma_settings', []);
	}

	/**
	 * @return string|null
	 */
	public function getEndpointUrl(): ?string
	{
		return $this->settings['irma_server_endpoint_url'] ?? null;
	}
}
