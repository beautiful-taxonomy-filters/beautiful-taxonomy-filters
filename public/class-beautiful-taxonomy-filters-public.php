<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */
class Beautiful_Taxonomy_Filters_Public {

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
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		//the basic stylesheet that should always be loaded! For select2 to display properly
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.css', array(), $this->version, 'all' );
		
		//Get user selected style
		$selected_style = get_option('beautiful_taxonomy_filters_styles');
		switch ($selected_style){
			case 'basic':
				//We wont load anything, let the allmighty user decide!
				break;
			case 'light-material':
				wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-light-material.css', array(), $this->version, 'all' );
				break;
			case 'dark-material':
				wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-dark-material.css', array(), $this->version, 'all' );
				break;
			
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/beautiful-taxonomy-filters-public.js', array( 'select2' ), $this->version, false );

	}
	
	
	/**
	 * Output any overriding CSS by the user from plugin settings
	 *
	 * @since    1.0.0
	 */
	public function custom_css() {
		
		$custom_css = get_option('beautiful_taxonomy_filters_custom_css');
		if($custom_css)
			echo '<style type="text/css">' . $custom_css . '</style>';

	}
	
	
	
	/**
	* Runs on template_include filter. Check for $POST values coming from the filter and add them to the url
	*
	* @since 1.0.0
	*/
	public function catch_filter_values(){
		//If we don't have an url, this wont work!
		$referer = (isset($_POST['site-url']) ? $_POST['site-url'] : false);
		if(!$referer)
			return;
		
		//get current post type archive
		$current_post_type = get_post_type();
		//base url
		$new_url = $referer . '/' . $current_post_type . '/';
		
		//Get the taxonomies of the current post type
		$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');
		if($current_taxonomies){
			foreach($current_taxonomies as $key => $value){
				
				//check for each taxonomy as a $_POST variable. 
				//If it exists we want to append it along with the value (term) it has.
				$term = (isset($_POST['select-'.$key]) ? $_POST['select-'.$key] : false);
				if($term){
					$new_url .= $key . '/' . $term . '/';
				}
				
			}
		}
		//perform a safe redirect
		wp_safe_redirect($new_url);
		exit;
	}
	
	
	/**
	* Public function to return the filters for the current post type archive. 
	*
	* @since 1.0.0
	*/
	public static function get_beautiful_filters(){
	
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-display.php';
		
	}

}
