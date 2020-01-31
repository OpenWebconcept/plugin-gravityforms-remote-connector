<?php

namespace Yard\Foundation;

abstract class ServiceProvider
{

    /**
     * Instance of the plugin.
     *
     * @var \Yard\Foundation\Plugin
     */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Register the service provider.
     */
    abstract public function register();
}
