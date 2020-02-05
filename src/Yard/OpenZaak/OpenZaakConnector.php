<?php

namespace Yard\OpenZaak;

use Yard\Connector\BaseConnector;

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
     * Handle the sending of the request.
     *
     * @return void
     */
    public function send()
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
