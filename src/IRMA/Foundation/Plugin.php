<?php

namespace Yard\IRMA\Foundation;

use Exception;

class Plugin
{
    /**
     * Name of the plugin.
     *
     * @var string
     */
    const NAME = 'irma-wp';

    /**
     * Version of the plugin.
     *
     * @var string
     */
    const VERSION = '0.0.4';

    /**
     * Path to the root of the plugin.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Instance of the configuration repository.
     *
     * @var \Yard\IRMA\Foundation\Config
     */
    public $config;

    /**
     * Instance of the hook loader.
     */
    public $loader;

    /**
     * Constructs the plugin.
     *
     * Create startup hooks and tear down hooks.
     * Boot up admin and frontend functionality.
     * Register the actions and filters from the loader.
     *
     * @param string $rootPath
     */
    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Boot the plugin.
     */
    public function boot()
    {
        $this->config = new Config($this->rootPath.'/config');
        $this->config->boot();

        $this->loader = Loader::getInstance();

        $this->bootServiceProviders();

        $this->loader->addAction('wp_enqueue_scripts', $this, 'enqueueScripts');
        $this->loader->addAction('admin_enqueue_scripts', $this, 'enqueueAdminScripts');
        $this->loader->register();
    }

    /**
     * Enqueue scripts within WordPress.
     */
    public function enqueueScripts()
    {
        wp_enqueue_style('irma-wp', $this->resourceUrl('irma-wp.css'), false);
        wp_enqueue_script('irma-js', $this->resourceUrl('irma.js'), false);
    }

    /**
     * Enqueue scripts for the WordPress Admin.
     */
    public function enqueueAdminScripts()
    {
        wp_enqueue_style('irma-admin-wp', $this->resourceUrl('irma-admin.css'), false);

        // only allow bootstrap on the IRMA settings page
        if (isset($_GET['page']) && 'irma' == $_GET['page']) {
            // CSS Bootstrap
            wp_register_style('irma-wp_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
            wp_enqueue_style('irma-wp_bootstrap');
        }
    }

    /**
     * Get the name of the plugin.
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Get the version of the plugin.
     *
     * @return string
     */
    public function getVersion()
    {
        return static::VERSION;
    }

    /**
     * Get the root path of the plugin.
     *
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * Get the path to a particular resource.
     *
     * @param $file
     *
     * @return string
     */
    public function resourceUrl($file)
    {
        return plugins_url('resources/'.$file, 'irma-wp/plugin.php');
    }

    /**
     * Boot service providers.
     */
    protected function bootServiceProviders()
    {
        $services = $this->config->get('core.providers');

        foreach ($services as $service) {
            // Only boot global service providers here.
            if (is_array($service)) {
                continue;
            }

            $service = new $service($this);

            if (!$service instanceof ServiceProvider) {
                throw new Exception('Provider must extend ServiceProvider.');
            }

            $service->register();
        }
    }
}
