<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://julienliabeuf.com
 * @since             0.1.0
 * @package           Pipelab
 *
 * @wordpress-plugin
 * Plugin Name:       Pipelab
 * Plugin URI:        https://julienliabeuf.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.1.0
 * Author:            Julien Liabeuf
 * Author URI:        https://julienliabeuf.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pipelab
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PIPELAB_VERSION', '0.1.0' );
define( 'PIPELAB_DB_VERSION', '1' );
define( 'PIPELAB_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function activate_pipelab() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	Pipelab\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function deactivate_pipelab() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	Pipelab\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pipelab' );
register_deactivation_hook( __FILE__, 'deactivate_pipelab' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pipelab.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function pipelab() {
	return Pipelab::instance();
}

pipelab();
