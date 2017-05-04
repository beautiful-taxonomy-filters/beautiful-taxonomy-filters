<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://tigerton.se
 * @since             1.0.0
 * @package           Beautiful_Taxonomy_Filters
 *
 * @wordpress-plugin
 * Plugin Name:       Beautiful taxonomy filter
 * Plugin URI:        http://tigerton.se
 * Description:       Supercharges your custom post type archives by letting visitors filter the posts by their terms/categories.
 * Version:           2.3.1
 * Author:            Jonathan de Jong
 * Author URI:        https://github.com/jonathan-dejong
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       beautiful-taxonomy-filters
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-beautiful-taxonomy-filters-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-beautiful-taxonomy-filters-deactivator.php';

/** This action is documented in includes/class-beautiful-taxonomy-filters-activator.php */
register_activation_hook( __FILE__, array( 'Beautiful_Taxonomy_Filters_Activator', 'activate' ) );

/** This action is documented in includes/class-beautiful-taxonomy-filters-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Beautiful_Taxonomy_Filters_Deactivator', 'deactivate' ) );

/** The template tags accessible for public use */
require_once plugin_dir_path( __FILE__ ) . 'public/beautiful-taxonomy-filters-functions.php';

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-beautiful-taxonomy-filters.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Beautiful_Taxonomy_Filters() {

	$plugin = new Beautiful_Taxonomy_Filters();
	$plugin->run();

}
run_Beautiful_Taxonomy_Filters();
