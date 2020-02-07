<?php

namespace Yard\OpenZaak;

use Yard\Connector\BaseConnector;
use Yard\OpenZaak\Client\OpenZaakClient;

class OpenZaakConnector extends BaseConnector
{
    /**
      * Register the Connector with GravityForms.
      *
      * @return void
      */
    public function register()
    {
    }

    /**
     * Get the client of the connector.
     *
     * @return OpenZaakClient
     */
    public function client()
    {
        return new OpenZaakClient(SettingsManager::make()->find('openzaak_url'), SettingsManager::make()->find('openzaak_token'));
    }

    /**
     * Handle the sending of the request.
     *
     * @var array $payload
     * @return array
     */
    public function send(array $payload = []): array
    {
        return $this->client()->post($payload);
    }

    /**
     * Should the connector be processed.
     *
     * @return bool
     */
    public function shouldProcess(): bool
    {
        return true;
    }
}
