<?php

namespace Yard\IRMA;

use Yard\Settings\SettingsManager;

class IRMASettingsManager extends SettingsManager
{
    protected $key = 'gravityformsaddon_irma-addon_settings';

    /**
     * Get the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointUrl(): ?string
    {
        return $this->settings['irma_server_endpoint'] ?? null;
    }

    /**
     * Get the token of the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointToken(): ?string
    {
        return $this->settings['irma_server_token'] ?? null;
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
}
