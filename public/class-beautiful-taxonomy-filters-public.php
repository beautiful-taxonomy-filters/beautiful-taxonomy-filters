<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
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
	* Appends the already existing GET parameters to the url. 
	* This allows for custom parameters to carry on to the filtered page
	* @since 1.0.0
	*/
	private function append_get_parameters($new_url){
		if(!empty($_GET)){
			$previous_parameters = $_GET;
			$i = 0;
			foreach($previous_parameters as $key => $value){
				$new_url .= ($i == 0 ? '?' : '&');
				$new_url .= $key . '=' . $value;
				$i++;
			}
		}
		return $new_url;
	}
	
	
	/**
	* Runs on template_include filter. Check for $POST values coming from the filter and add them to the url
	* Also check for custom GET parameters and reattach them to the url to support combination with other functionalities
	* @since 1.0.0
	*/
	public function catch_filter_values(){
		//If we don't have an url, this wont work!
		$referer = (isset($_POST['site-url']) ? $_POST['site-url'] : false);
		if(!$referer)
			return;
		
		//get current post type archive
		if(isset($_POST['post_type']) && $_POST['post_type'] != ''){
			$current_post_type = $_POST['post_type'];
		}else{ //If there was no post type from the form (for some reason), try to get it anyway!
			$current_post_type = get_post_type();
			//If there is no post type found OR post type found is a page, try to find the post type from the current template instead!
			if(!$current_post_type || $current_post_type == 'page'){
				global $template;
				$template_name = explode('-', basename( $template, '.php' ));
				if (in_array('archive', $template_name) && count($template_name) > 1) {
					//We found the post type in the template!
					$current_post_type = $template_name[1];
				}else{
					//didnt find the post type in the template, fall back to the wp_query!
					global $wp_query;
					if($wp_query->query['post_type'] != ''){
						$current_post_type = $wp_query->query['post_type'];	
					}
				}
			}
		}
		
		
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
		
		$new_url = $this->append_get_parameters($new_url);
		
		//perform a redirect to the new filtered url
		wp_redirect($new_url);
		exit;
	}
	
	
	/**
	* Public function to return the filters for the current post type archive. 
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters(){
		//Fetch the plugins options
		//Apply filters on them to let users modify the options before they're being used!
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') ); 
		$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') ); 

		//If there's no post types, bail early!
		if(!$post_types) 
			return;

		//get current post type archive
		if(isset($_POST['post_type']) && $_POST['post_type'] != ''){
			$current_post_type = $_POST['post_type'];
		}else{ //If there was no post type from the form (for some reason), try to get it anyway!
			$current_post_type = get_post_type();
			//If there is no post type found OR post type found is a page, try to find the post type from the current template instead!
			if(!$current_post_type || $current_post_type == 'page'){
				global $template;
				$template_name = explode('-', basename( $template, '.php' ));
				if (in_array('archive', $template_name) && count($template_name) > 1) {
					$current_post_type = $template_name[1];
				}else{
					global $wp_query;
					if($wp_query->query['post_type'] != ''){
						$current_post_type = $wp_query->query['post_type'];	
					}
				}
			}
		}

		//Get the taxonomies of the current post type
		$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');
		//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
		if($current_taxonomies && $excluded_taxonomies){
			foreach($current_taxonomies as $key => $value){
				if(in_array($key, $excluded_taxonomies)){
					unset($current_taxonomies[$key]);
				}
			}
		}
		//On a post type that we want the filter on, and we have atleast one valid taxonomy
		if(in_array($current_post_type, $post_types) && !empty($current_taxonomies)){

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-display.php';
		}
		
	}
	
	/**
	* Public function to return information about the currently active filters
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters_info(){
		global $wp_query;
		$taxonomies = $wp_query->tax_query->queries;
		//as long as we have some taxonomies, lets show the info!
		if(!empty($taxonomies)){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-info-display.php';	
		}
		
	}

}
