<?php

namespace IRMA\WP\Settings;

class SettingsManager
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $settingsDecos;

    public function __construct()
    {
        $this->settings = get_option('irma_settings', []);
        $this->settingsDecos = get_option('irma_settings_decos', []);
    }

    /**
     * Get the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointUrl(): ?string
    {
        return $this->settings['irma_server_endpoint_url'] ?? null;
    }

    /**
     * Get the token of the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointToken(): ?string
    {
        return $this->settings['irma_server_endpoint_token'] ?? null;
    }

    /**
     * Get the RSIN code.
     *`.
     *
     * @return string|null
     */
    public function getRISN(): ?string
    {
        return $this->settings['irma_wp_rsin'] ?? null;
    }

    /**
     * Get the create case URL.
     *`.
     *
     * @return string|null
     */
    public function createCaseURL(): ?string
    {
        return $this->settings['createCaseURL'] ?? null;
    }

    /**
     * Get the create case object URL.
     *`.
     *
     * @return string|null
     */
    public function createCaseObjectURL(): ?string
    {
        return $this->settings['createCaseObjectURL'] ?? null;
    }

    /**
     * Get the create case property URL.
     *`.
     *
     * @return string|null
     */
    public function createCasePropertyURL(): ?string
    {
        return $this->settings['createCasePropertyURL'] ?? null;
    }

    /**
     * Get the RSIN code.
     *`.
     *
     * @return string|null
     */
    public function getAttributeBSN(): ?string
    {
        return $this->settings['irma_wp_bsn_attribute'] ?? null;
    }

    /**
     * Get the RSIN code.
     *`.
     *
     * @return array
     */
    public function getAttributeDecos(): array
    {
        return $this->settingsDecos ?? [];
    }
}
