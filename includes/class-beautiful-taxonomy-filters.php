<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */
class Beautiful_Taxonomy_Filters {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Beautiful_Taxonomy_Filters_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $Beautiful_Taxonomy_Filters    The string used to uniquely identify this plugin.
	 */
	protected $Beautiful_Taxonomy_Filters;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->Beautiful_Taxonomy_Filters = 'beautiful-taxonomy-filters';
		$this->version = '2.3.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Beautiful_Taxonomy_Filters_Loader. Orchestrates the hooks of the plugin.
	 * - Beautiful_Taxonomy_Filters_i18n. Defines internationalization functionality.
	 * - Beautiful_Taxonomy_Filters_Admin. Defines all hooks for the dashboard.
	 * - Beautiful_Taxonomy_Filters_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-beautiful-taxonomy-filters-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-beautiful-taxonomy-filters-i18n.php';

		/**
		 * BTF API functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-beautiful-taxonomy-filters-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-beautiful-taxonomy-filters-public.php';

		/**
		 * The class responsible for running the wp rewrite rules
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-beautiful-taxonomy-filters-rewrite-rules.php';

		/**
		 * The class that contains our custom wp_get_categories walker
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-beautiful-taxonomy-filters-walker.php';

		/**
		 * Our widget class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/beautiful-taxonomy-filters-widget.php';

		/**
		 * Our info widget class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/beautiful-taxonomy-filters-info-widget.php';

		$this->loader = new Beautiful_Taxonomy_Filters_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Beautiful_Taxonomy_Filters_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Beautiful_Taxonomy_Filters_i18n();
		$plugin_i18n->set_domain( $this->get_Beautiful_Taxonomy_Filters() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Beautiful_Taxonomy_Filters_Admin( $this->get_Beautiful_Taxonomy_Filters(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'generate_rewrite_rules', $plugin_admin, 'add_rewrite_rules' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_api_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'check_update_version' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'show_admin_notice' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Beautiful_Taxonomy_Filters_Public( $this->get_Beautiful_Taxonomy_Filters(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'custom_css' );
		$this->loader->add_action( 'loop_start', $plugin_public, 'automagic_insertion' ); //sounds dirty...
		$this->loader->add_filter( 'wp_dropdown_cats', $plugin_public, 'modify_select_elements', 10, 2 );
		$this->loader->add_filter( 'template_redirect', $plugin_public, 'catch_filter_values' );
		$this->loader->add_action( 'wp_ajax_update_filters_callback', $plugin_public, 'update_filters_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_filters_callback', $plugin_public, 'update_filters_callback' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_classes' );

		//Our own custom actions to let users insert our code into their themes in a friendly way
		$this->loader->add_action( 'show_beautiful_filters', $plugin_public, 'beautiful_filters', 10, 1 );
		$this->loader->add_action( 'show_beautiful_filters_info', $plugin_public, 'beautiful_filters_info' );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_Beautiful_Taxonomy_Filters() {
		return $this->Beautiful_Taxonomy_Filters;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Beautiful_Taxonomy_Filters_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
