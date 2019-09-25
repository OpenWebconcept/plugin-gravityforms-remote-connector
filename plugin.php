<?php

/**
 * Plugin Name: IRMA for WordPress
 * Plugin URI: https://privacybydesign.foundation/
 * Description: Integrates WordPress with the IRMA identity platform.
 * Author: Privacy by Design
 * Author URI: https://www.yardinternet.nl
 * Version: 0.0.0
 * License: GPL3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: irma-wp
 * Domain Path: /languages
 */

use IRMA\WP\Autoloader;
use IRMA\WP\Actions;
use IRMA\WP\Foundation\Plugin;

define('IRMA_WP_VERSION', '0.0.0');
define('IRMA_WP__PLUGIN_URL', __DIR__);
define('IRMA_WP_RSIN_BUREN', '807287684');

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
	die;
}

/**
 * Manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';

$autoloader = new Autoloader();

/**
 * Manual loaded file: actions.
 */
require_once __DIR__ . '/Actions.php';

$actions = new Actions();

/**
 * Begin execution of the plugin.
 */
$plugin = (new Plugin(__DIR__))->boot();
