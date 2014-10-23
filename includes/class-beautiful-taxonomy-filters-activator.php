<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Your Name <email@example.com>
 */
class Beautiful_Taxonomy_Filters_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//flush rewrite rules. Just to make sure our rewrite rules from an earlier activation are applied again!
		flush_rewrite_rules();
		//would want to use flush_rewrite_rules only but that does not work for some reason??
		delete_option('rewrite_rules');

	}

}
