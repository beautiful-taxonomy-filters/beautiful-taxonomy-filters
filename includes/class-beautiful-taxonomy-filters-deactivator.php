<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Your Name <email@example.com>
 */
class Beautiful_Taxonomy_Filters_Deactivator {

	/**
	 * The function which handles deactivation of our plugin
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		//flush rewrite rules. Don't want no lingering stuff!
		flush_rewrite_rules();
		//would want to use flush_rewrite_rules only but that does not work for some reason??
		delete_option('rewrite_rules');
		//Delete plugin version
		//delete_option('beautiful_taxonomy_filters_version');
	}

}
