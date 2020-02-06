<?php

namespace Yard\GravityForms;

use Yard\Connector\BaseConnector;

class GravityFormsDefaultConnector extends BaseConnector
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
     */
    public function client()
    {
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
        return false;
    }
}
