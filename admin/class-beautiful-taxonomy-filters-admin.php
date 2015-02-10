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
	 * Create our custom filter widget
	 *
	 * @since    1.0.0
	 */
	public function register_widgets() {
		
	    register_widget( 'Beautiful_Taxonomy_Filters_Widget' );
	    register_widget( 'Beautiful_Taxonomy_Filters_Info_Widget' );
	    
	}
 	
	
	/**
	 * Register the settings page
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
	    add_options_page( 'Beautiful Taxonomy Filters', __('Taxonomy Filters', 'beautiful-taxonomy-filters'), 'manage_options', 'taxonomy-filters', array($this, 'create_admin_interface'));
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
		
		/*
		* SECTIONS
		*/
		
		// Add the Setup settings section to our plugins settings page
	 	add_settings_section(
			'taxonomy_filters_general_settings_section',
			__('Setup settings', 'beautiful-taxonomy-filters'),
			array($this, 'setting_section_callback_function'),
			'taxonomy-filters'
		);
		
		// Add the Filter module settings section to our plugins settings page
	 	add_settings_section(
			'taxonomy_filters_module_settings_section',
			__('Filter module settings', 'beautiful-taxonomy-filters'),
			array($this, 'filters_module_setting_section_callback_function'),
			'taxonomy-filters'
		);
		
		// Add the Style Settings section to our plugins settings page
	 	add_settings_section(
			'taxonomy_filters_styling_settings_section',
			__('Style settings', 'beautiful-taxonomy-filters'),
			array($this, 'style_setting_section_callback_function'),
			'taxonomy-filters'
		);
		
		
		/*
		* SETTINGS FIELDS
		*/
				
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
		
		// Add checkbox to let users choose to disable select2 library
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_select2',
			__('Disable the select2 library:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_select2_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Add checkbox to let users choose to display a "clear all" link on filter
	 	add_settings_field(
			'beautiful_taxonomy_filters_clear_all',
			__('Enable a "Clear all" link:', 'beautiful-taxonomy-filters'),
			array($this, 'clear_all_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Hide empty terms/categories from the dropdowns
	 	add_settings_field(
			'beautiful_taxonomy_filters_hide_empty',
			__('Hide empty terms in the dropdowns:', 'beautiful-taxonomy-filters'),
			array($this, 'hide_empty_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Show count next to term
	 	add_settings_field(
			'beautiful_taxonomy_filters_show_count',
			__('Show post count next to term name:', 'beautiful-taxonomy-filters'),
			array($this, 'show_count_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Select either an "All terms" option or a placeholder with a deselect
	 	add_settings_field(
			'beautiful_taxonomy_filters_dropdown_behaviour',
			__('Dropdown deselect/default behaviour:', 'beautiful-taxonomy-filters'),
			array($this, 'dropdown_behaviour_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Automagically insert the modules using loop_start hook
	 	add_settings_field(
			'beautiful_taxonomy_filters_automagic',
			__('Automagically insert the modules in the archives:', 'beautiful-taxonomy-filters'),
			array($this, 'automagic_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_module_settings_section'
		);
		
		// Add checkbox to let users choose to disable the "active filters" heading
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_heading',
			__('Disable the active filters heading:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_heading_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_styling_settings_section'
		);
		
		// Add checkbox to let users choose to disable the "Result of filter" paragraph
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_postcount',
			__('Disable the filterinfo modules post count:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_postcount_setting_callback_function'),
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
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_disable_select2' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_clear_all' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_show_count' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_hide_empty' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_disable_heading' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_disable_postcount' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_dropdown_behaviour' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_automagic' );
		
		
	}
	
	//Our callback functions
	
	/* SECTIONS */
	function setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-section-display.php';
	}
	function style_setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-style-section-display.php';
	}
	
	function filters_module_setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-module-section-display.php';
	}
	
	/* SETTINGS FIELDS */
	function post_type_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-post_type-settings-display.php';
 	}
 	
 	function taxonomies_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-taxonomies-settings-display.php';
 	}
 	
 	function disable_select2_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-disable-select2-settings-display.php';
 	}
 	
 	function clear_all_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-clear-all-settings-display.php';
 	}
 	
 	function hide_empty_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-hide-empty-settings-display.php';
 	}
 	
 	function show_count_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-show-count-settings-display.php';
 	}
 	
 	function dropdown_behaviour_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-dropdown-behaviour-settings-display.php';
 	}
 	
 	function automagic_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-automagic-settings-display.php';
 	}
 	
 	function disable_heading_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-disable-heading-settings-display.php';
 	}
 	
 	function disable_postcount_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-disable-postcount-settings-display.php';
 	}
 	
 	function styles_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-styles-settings-display.php';
 	}
 	
 	function custom_css_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/beautiful-taxonomy-filters-custom-css-settings-display.php';
 	}

}
?>