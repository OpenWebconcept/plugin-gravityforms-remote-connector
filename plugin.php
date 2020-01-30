<?php
/**
 * Plugin Name: IRMA for WordPress
 * Plugin URI: https://privacybydesign.foundation/
 * Description: Integrates WordPress with the IRMA identity platform.
 * Author: Privacy by Design
 * Author URI: https://www.yardinternet.nl
 * Version: 0.4
 * License: GPL3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: irma-wp
 * Domain Path: /languages.
 */
use Yard\IRMA\Actions;
use Yard\IRMA\Autoloader;
use Yard\IRMA\Foundation\Plugin;

define('IRMA_WP_VERSION', '0.4');
define('IRMA_WP__PLUGIN_URL', __DIR__);

/*
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__.'/autoloader.php';

$autoloader = new Autoloader();
$actions    = new Actions();

/**
 * Begin execution of the plugin.
 */
$plugin = (new Plugin(__DIR__))->boot();
