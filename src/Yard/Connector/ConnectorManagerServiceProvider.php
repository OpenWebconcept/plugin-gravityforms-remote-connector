<?php

namespace Yard\Connector;

use Yard\Foundation\ServiceProvider;

class ConnectorManagerServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities.
     */
    public function register()
    {
        (new ConnectorManager())->registerConnectors();
    }
}
