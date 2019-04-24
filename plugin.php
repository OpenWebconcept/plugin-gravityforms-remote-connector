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
use IRMA\WP\Foundation\Plugin;

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
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
$plugin = (new Plugin(__DIR__))->boot();

// add_action('plugins_loaded', function () {
// }, 10);
