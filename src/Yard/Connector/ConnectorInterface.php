<?php

namespace Yard\Connector;

interface ConnectorInterface
{
    /**
     * Register the Connector with GravityForms.
     *
     * @return void
     */
    public function register();

    /**
     * Handle the sending of the request.
     *
     * @return void
     */
    public function send();

    /**
     * Should the connector be processed.
     *
     * @return bool
     */
    public function shouldProcess(): bool;
}
