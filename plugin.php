<?php
/**
 * Plugin Name: GravityForms Remote Connector
 * Plugin URI: https://www.yard.nl
 * Description: Integrates WordPress with the IRMA identity platform.
 * Author: Yard Digital Agency
 * Author URI: https://www.yard.nl
 * Version: 0.1
 * License: GPL3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: gravityforms-remote-connector
 * Domain Path: /languages.
 */
use Yard\Autoloader;
use Yard\Foundation\Plugin;

/*
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

define('GF_R_C_PLUGIN_FILE', __FILE__);
define('GF_R_C_PLUGIN_SLUG', 'gravityforms-remote-connector');
define('GF_R_C_ROOT_PATH', __DIR__);
define('GF_R_C_VERSION', '0.4');

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__.'/autoloader.php';

$autoloader = new Autoloader();

/**
 * Begin execution of the plugin.
 */
$plugin = (new Plugin(__DIR__))->boot();
