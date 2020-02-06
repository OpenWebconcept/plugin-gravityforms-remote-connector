<?php

namespace Yard\IRMA;

use Yard\Connector\BaseConnector;

class IRMAConnector extends BaseConnector
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
     * @return void
     */
    public function client()
    {
        return;
    }

    /**
     * Handle the sending of the request.
     *
     * @var array $payload
     * @return void
     */
    public function send(array $payload = [])
    {
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
