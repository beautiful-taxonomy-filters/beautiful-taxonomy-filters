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
	 * Flush rewrite rules
	 * @since    1.0.0
	 */
	public static function activate() {
		//flush rewrite rules. Just to make sure our rewrite rules from an earlier activation are applied again!
		flush_rewrite_rules();
		//would want to use flush_rewrite_rules only but that does not work for some reason??
		delete_option('rewrite_rules');

		//Find if there's already some post types enabled
		$post_types = get_option('beautiful_taxonomy_filters_post_types');
		//If option does not exist or is empty. Show a message to help them along
		if( !$post_types || empty( $post_types ) ){

			$btf = new Beautiful_Taxonomy_Filters();
			$btf_admin = new Beautiful_Taxonomy_Filters_Admin($btf->get_Beautiful_Taxonomy_Filters(), $btf->get_version());

			$message = sprintf( wp_kses( __( 'Beautiful Taxonomy Filters needs some <a href="%s">basic setup</a>.', 'beautiful-taxonomy-filters' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url() . 'options-general.php?page=taxonomy-filters&tab=basic' ) );

			$btf_admin->add_admin_notice( $message );

		}
	}

}