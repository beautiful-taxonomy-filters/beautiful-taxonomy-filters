<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */

class Beautiful_Taxonomy_Filters_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}
	
	
	/**
	 * Initiates our custom rewrite class
	 *
	 * @since    1.0.0
	 */
 	public function add_rewrite_rules(){
 	
	 	global $wp_rewrite;
	 	//get the saved options
	 	$post_types = get_option('beautiful_taxonomy_filters_post_types');
	 	$taxonomies = get_option('beautiful_taxonomy_filters_taxonomies');
	 	if($post_types){
	 		//instantiate the rewrite rules class
		 	$rewrite_rule_class = new Beautiful_Taxonomy_Filters_Rewrite_Rules();
		 	foreach($post_types as $post_type){
		 		//Run the function for each selected post type
				$new_rules = $rewrite_rule_class->generate_rewrite_rules($post_type, $taxonomies);
				$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
			}
		}
				
 	}
 	
	
	/**
	 * Register the settings page
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
	    add_options_page( 'Beautiful Taxonomy Filters', 'Taxonomy Filters', 'manage_options', 'taxonomy-filters', array($this, 'create_admin_interface'));
	}
	
	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function create_admin_interface(){	
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-admin-display.php';
		
	}
	
	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){
		
		// Add the Setup settings section to our plugins settings page
	 	add_settings_section(
			'taxonomy_filters_general_settings_section',
			__('Setup settings', 'beautiful-taxonomy-filters'),
			array($this, 'setting_section_callback_function'),
			'taxonomy-filters'
		);
		
		// Add the Style Settings section to our plugins settings page
	 	add_settings_section(
			'taxonomy_filters_styling_settings_section',
			__('Style settings', 'beautiful-taxonomy-filters'),
			array($this, 'style_setting_section_callback_function'),
			'taxonomy-filters'
		);
				
		// Add the field with the post types
	 	add_settings_field(
			'beautiful_taxonomy_filters_post_types',
			__('Activate for these post types:', 'beautiful-taxonomy-filters'),
			array($this, 'post_type_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_general_settings_section'
		);
		
		// Add the field with the taxonomies
	 	add_settings_field(
			'beautiful_taxonomy_filters_taxonomies',
			__('Exclude these taxonomies:', 'beautiful-taxonomy-filters'),
			array($this, 'taxonomies_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_general_settings_section'
		);
		
		// Add checkbox to let users choose to display a "clear all" link on filter
	 	add_settings_field(
			'beautiful_taxonomy_filters_clear_all',
			__('Enable a "Clear all" link:', 'beautiful-taxonomy-filters'),
			array($this, 'clear_all_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_styling_settings_section'
		);
		
		// Add checkbox to let users choose to disable the "active filters" heading
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_heading',
			__('Disable the active filters heading:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_heading_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_styling_settings_section'
		);
		
		// Add the field with the selectable styles
	 	add_settings_field(
			'beautiful_taxonomy_filters_styles',
			__('Styling:', 'beautiful-taxonomy-filters'),
			array($this, 'styles_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_styling_settings_section'
		);
		
		// Add the textarea where the user can put custom CSS
	 	add_settings_field(
			'beautiful_taxonomy_filters_custom_css',
			__('Custom CSS Styling:', 'beautiful-taxonomy-filters'),
			array($this, 'custom_css_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_styling_settings_section'
		);
		
		// Add a new setting to the options table
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_post_types' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_taxonomies' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_styles' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_custom_css' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_clear_all' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_disable_heading' );
		
	}
	
	//Our callback functions
	function setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-section-display.php';
	}
	function style_setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-style-section-display.php';
	}
	
	function post_type_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-post_type-settings-display.php';
 	}
 	
 	function taxonomies_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-taxonomies-settings-display.php';
 	}
 	
 	function clear_all_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-clear-all-settings-display.php';
 	}
 	
 	function disable_heading_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-disable-heading-settings-display.php';
 	}
 	
 	function styles_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-styles-settings-display.php';
 	}
 	
 	function custom_css_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-custom-css-settings-display.php';
 	}

}
?>