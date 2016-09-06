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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.2.8
	 */
	public function enqueue_styles() {
		global $pagenow;
		if( $pagenow == 'options-general.php' ){
			wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Add admin notices
	 *
	 * @since	1.2.8
	 */
	public function add_admin_notice( $message ){

		$notices = get_transient('btf_notice');
		if( $notices === false ){
			$new_notices[] = $message;
			set_transient('btf_notice', $new_notices, 120);
		}else{
			$notices[] = $message;
			set_transient('btf_notice', $notices, 120);
		}

	}


	/**
	 * Show admin notices
	 * @since	1.2.8
	 */
	public function show_admin_notice(){

		$notices = get_transient('btf_notice');
		if( $notices !== false ){
			foreach( $notices as $notice ){
				echo '<div class="update-nag"><p>' . $notice . '</p></div>';
			}

			delete_transient('btf_notice');
		}

	}

	/**
	 * Check plugin version on update
	 *
	 * @since	1.2.8
	 */
	public function check_update_version(){

		// Current version
		$current_version = get_option('beautiful_taxonomy_filters_version');

		//Their version is older
		if( $current_version && version_compare($current_version, $this->version, '<') ){

			//Older than 1.2.8
			if( version_compare($current_version, '1.2.8', '<') ){

				$message = sprintf( wp_kses( __( 'Beautiful Taxonomy Filters has had a change in the settings structure. Please head over to the <a href="%s">advanced tab</a> to make sure everything is correct.', 'beautiful-taxonomy-filters' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url() . 'options-general.php?page=taxonomy-filters&tab=advanced' ) );
				$this->add_admin_notice( $message );

			}

			//Older than 2.0.0
			if( version_compare($current_version, '2.0.0', '<') ){

				$message = sprintf( wp_kses( __( 'Beautiful Taxonomy Filters now supports conditional dropdowns to avoid empty results. Head over to the <a href="%s">advanced tab</a> to enable the beta feature.', 'beautiful-taxonomy-filters' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url() . 'options-general.php?page=taxonomy-filters&tab=advanced' ) );
				$this->add_admin_notice( $message );

			}

			//Finally update the current version
			update_option('beautiful_taxonomy_filters_version', $this->version);

		}


		if( !$current_version ){

			$message = sprintf( wp_kses( __( 'Beautiful Taxonomy Filters has had a change in the settings structure. Please head over to the <a href="%s">advanced tab</a> to make sure everything is correct.', 'beautiful-taxonomy-filters' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url() . 'options-general.php?page=taxonomy-filters&tab=advanced' ) );
			$this->add_admin_notice( $message );

			//Make sure they now have a current version in their DB
			update_option('beautiful_taxonomy_filters_version', $this->version);

		}

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
	 	//Also make sure we don't try to output the builtin taxonomies since they cannot be supported
		if(is_array($taxonomies)){
			array_push($taxonomies, 'category', 'tag');
		}else{
			$taxonomies = array(
				'category',
				'tag'
			);
		}

		//Polylang support
		if( function_exists('pll_current_language') ) {
			array_push($taxonomies, 'language');
		}

	 	if( $post_types ) {
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

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/admin-display.php';

	}

	/**
	 * Creates our settings sections with fields etc.
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){

		/**
		 * Sections functions
		 */

		// The basic section
	 	add_settings_section(
			'taxonomy_filters_basic_settings_section',
			__('Basic Setup', 'beautiful-taxonomy-filters'),
			array($this, 'setting_section_callback_function'),
			'taxonomy-filters'
		);

		// The Advanced section
	 	add_settings_section(
			'taxonomy_filters_advanced_settings_section',
			__('Advanced settings', 'beautiful-taxonomy-filters'),
				array($this, 'advanced_setting_section_callback_function'),
			'advanced-taxonomy-filters'
		);


		/**
		 * Basic settings
		 */

		// Add the field with the post types
	 	add_settings_field(
			'beautiful_taxonomy_filters_post_types',
			'<span class="btf-tooltip" title="' . __('Only these post types will have custom rewrite rules added to them and the filter module will only appear for these archives.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Activate for these post types:', 'beautiful-taxonomy-filters'),
			array($this, 'post_type_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_basic_settings_section'
		);

		// Add the field with the taxonomies
	 	add_settings_field(
			'beautiful_taxonomy_filters_taxonomies',
			'<span class="btf-tooltip" title="' . __('Exclude taxonomies that is connected to your custom post types but should not be used for filtering.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Exclude these taxonomies:', 'beautiful-taxonomy-filters'),
			array($this, 'taxonomies_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_basic_settings_section'
		);

		// Automagically insert the modules using loop_start hook
	 	add_settings_field(
			'beautiful_taxonomy_filters_automagic',
			'<span class="btf-tooltip" title="' . __('Experimental feature!  Attempts to automagically insert the modules in your archive. If the placement isn\'t what you want you\'ll have to use one of the other methods.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Automagically insert the modules in the archives:', 'beautiful-taxonomy-filters'),
			array($this, 'automagic_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_basic_settings_section'
		);

		// Predefined styles
	 	add_settings_field(
			'beautiful_taxonomy_filters_styles',
			'<span class="btf-tooltip" title="' . __('Predefined styles for your site inspired by Google Material Design.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Styling:', 'beautiful-taxonomy-filters'),
			array($this, 'styles_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_basic_settings_section'
		);

		// Custom CSS
	 	add_settings_field(
			'beautiful_taxonomy_filters_custom_css',
			'<span class="btf-tooltip" title="' . __('Add your overriding styles directly to this textarea to do small tweaks of the predefined styles. Not recommended for major changes.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Custom CSS Styling:', 'beautiful-taxonomy-filters'),
			array($this, 'custom_css_setting_callback_function'),
			'taxonomy-filters',
			'taxonomy_filters_basic_settings_section'
		);

		//register basic settings
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_post_types' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_taxonomies' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_automagic' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_styles' );
		register_setting( 'taxonomy-filters', 'beautiful_taxonomy_filters_custom_css' );


		/**
		 * Advanced settings
		 */
		// Add checkbox to let users opt in to use the AJAX based dropdown relationship
	 	add_settings_field(
			'beautiful_taxonomy_filters_settings',
			'<span class="btf-tooltip" title="' . __('When selecting a term in one taxonomy dropdown the others will update accordingly making sure to only show terms that fit the current selection.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Enable conditional dropdown values:', 'beautiful-taxonomy-filters'),
			array($this, 'conditional_dropdowns_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Add checkbox to let users choose to disable select2 library
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_select2',
			'<span class="btf-tooltip" title="' . __('Disables the use of Select2 for improved dropdowns. Regular select elements will be used instead.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Disable the select2 library:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_select2_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Add checkbox to let users choose to disable select2 library
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_select2',
			'<span class="btf-tooltip" title="' . __('Disables the use of Select2 for improved dropdowns. Regular select elements will be used instead.', 'beautiful-taxonomy-filters') . '">?</span>' . __('Disable the select2 library:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_select2_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Add checkbox to let users choose to display a "clear all" link on filter
	 	add_settings_field(
			'beautiful_taxonomy_filters_clear_all',
			'<span class="btf-tooltip" title="' . __('Adds a link next to the submit button that clears all current filters.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Enable a "Clear all" link:', 'beautiful-taxonomy-filters'),
			array($this, 'clear_all_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Hide empty terms/categories from the dropdowns
	 	add_settings_field(
			'beautiful_taxonomy_filters_hide_empty',
			'<span class="btf-tooltip" title="' . __('Hides terms with no posts to them.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Hide empty terms in the dropdowns:', 'beautiful-taxonomy-filters'),
			array($this, 'hide_empty_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Show count next to term
	 	add_settings_field(
			'beautiful_taxonomy_filters_show_count',
			'<span class="btf-tooltip" title="' . __('Displays how many posts are in each term next to the terms name.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Show post count next to term name:', 'beautiful-taxonomy-filters'),
			array($this, 'show_count_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Show description next to term
	 	add_settings_field(
			'beautiful_taxonomy_filters_show_description',
			'<span class="btf-tooltip" title="' . __('Displays the terms description next to the term name in the dropdowns.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Show term description:', 'beautiful-taxonomy-filters'),
			array($this, 'show_description_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Select either an "All terms" option or a placeholder with a deselect
	 	add_settings_field(
			'beautiful_taxonomy_filters_dropdown_behaviour',
			'<span class="btf-tooltip" title="' . __('Either an Show all option at the top of the dropdown or, used with Select2, a placeholder text along with the ability to deselect the current term.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Dropdown deselect/default behaviour:', 'beautiful-taxonomy-filters'),
			array($this, 'dropdown_behaviour_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Add checkbox to let users choose to disable the "active filters" heading
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_heading',
			'<span class="btf-tooltip" title="' . __('Removes the heading for the filter info module.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Disable the active filters heading:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_heading_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		// Add checkbox to let users choose to disable the "Result of filter" paragraph
	 	add_settings_field(
			'beautiful_taxonomy_filters_disable_postcount',
			'<span class="btf-tooltip" title="' . __('Removes the post count in the filter info module for the active filter.', 'beautiful-taxonomy-filters') . '">?</span>' .__('Disable the filter info modules post count:', 'beautiful-taxonomy-filters'),
			array($this, 'disable_postcount_setting_callback_function'),
			'advanced-taxonomy-filters',
			'taxonomy_filters_advanced_settings_section'
		);

		//Register advanced settings
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_disable_select2' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_clear_all' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_show_count' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_show_description' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_hide_empty' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_disable_heading' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_disable_postcount' );
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_dropdown_behaviour' );

		/**
		 * Register the new setting that should eventually hold all settings as an associative array
		 */
		register_setting( 'advanced-taxonomy-filters', 'beautiful_taxonomy_filters_settings' );

	}

	/**
	 * Callback functions for settings
	 */


	/*
	 * Basic
	 */

	// The basic section
	function setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/basic-section-display.php';
	}

	// Select post types
	function post_type_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/post_type-settings-display.php';
 	}

 	// Exclude taxonomies
 	function taxonomies_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/taxonomies-settings-display.php';
 	}

 	// Automagic settings
 	function automagic_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/automagic-settings-display.php';
 	}

 	//Predefined styles
 	function styles_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/styles-settings-display.php';
 	}

 	//Custom CSS
 	function custom_css_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/basic/custom-css-settings-display.php';
 	}


 	/*
	 * Advanced
	 */

	// the advanced section
	function advanced_setting_section_callback_function(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/advanced-section-display.php';
	}

	// Disable Select2
 	function conditional_dropdowns_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/conditional-dropdowns-settings-display.php';
 	}

	// Disable Select2
 	function disable_select2_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/disable-select2-settings-display.php';
 	}

 	//Enable clear all link
 	function clear_all_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/clear-all-settings-display.php';
 	}

 	// Hide empty terms in dropdowns
 	function hide_empty_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/hide-empty-settings-display.php';
 	}

 	// Show post count in dropdowns
 	function show_count_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/show-count-settings-display.php';
 	}

 	// Show term description in dropdowns
 	function show_description_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/show-description-settings-display.php';
 	}

 	// Placeholder or "Show all" behaviour of dropdowns
 	function dropdown_behaviour_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/dropdown-behaviour-settings-display.php';
 	}

 	// Disable filter info modules heading
 	function disable_heading_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/disable-heading-settings-display.php';
 	}

 	// Disable filter info modules post count
 	function disable_postcount_setting_callback_function() {
 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/advanced/disable-postcount-settings-display.php';
 	}

}
?>