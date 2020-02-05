<?php

namespace Yard\Connector;

use Yard\Foundation\ServiceProvider;

class ConnectorManagerServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities.
     *
     * @return void
     */
    public function register(): void
    {
        (new ConnectorManager())->registerConnectors();
    }
}
