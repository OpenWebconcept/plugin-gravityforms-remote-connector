<?php

/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../../vendor/autoload.php';

define('WP_PLUGIN_DIR', __DIR__);
define('WP_DEBUG', false);

/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork(true);
\WP_Mock::bootstrap();

$GLOBALS['yard-blocks'] = [
    'active_plugins' => ['irma-wp/plugin.php'],
];
