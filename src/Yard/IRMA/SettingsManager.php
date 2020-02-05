<?php

namespace Yard\IRMA;

use Yard\Foundation\SettingsManager as BaseSettingsManager;

class SettingsManager extends BaseSettingsManager
{
    protected $key = 'gravityformsaddon_irma-addon_settings';

    /**
     * Get the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointUrl(): ?string
    {
        return $this->get('irma_server_endpoint', null);
    }

    /**
     * Get the token of the endpoint of the IRMA server.
     *
     * @return string|null
     */
    public function getEndpointToken(): ?string
    {
        return $this->get('irma_server_token', null);
    }

    /**
     * Get the RSIN code.
     *`.
     *
     * @return string|null
     */
    public function getAttributeBSN(): ?string
    {
        return $this->get('irma_bsn_attribute_field_name', null);
    }
}
