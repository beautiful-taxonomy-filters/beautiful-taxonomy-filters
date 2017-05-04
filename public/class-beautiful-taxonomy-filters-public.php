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

		$disable_select2 = (get_option('beautiful_taxonomy_filters_disable_select2') ? get_option('beautiful_taxonomy_filters_disable_select2') : false);

		if( !$disable_select2 ){
			//the basic stylesheet that should always be loaded! For select2 to display properly
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		}

		//BTFs own basic stylesheet. Should also always be loaded (very minimal)
		wp_enqueue_style( $this->name . '-basic', plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-base.min.css', array(), $this->version, 'all' );

		//Get user selected style
		$selected_style = get_option('beautiful_taxonomy_filters_styles');
		switch ($selected_style){
			case 'basic':
				//We wont load anything, let the almighty user decide!
				break;
			case 'light-material':
				wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-light-material.min.css', array(), $this->version, 'all' );
				break;
			case 'dark-material':
				wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-dark-material.min.css', array(), $this->version, 'all' );
				break;
			case 'simple':
				wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-simple.min.css', array(), $this->version, 'all' );
				break;

		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Getting some settings.
		 */
		$disable_select2 = ( get_option( 'beautiful_taxonomy_filters_disable_select2' ) ? get_option( 'beautiful_taxonomy_filters_disable_select2' ) : false );
		$settings = ( get_option( 'beautiful_taxonomy_filters_settings' ) ? get_option( 'beautiful_taxonomy_filters_settings' ) : false );
		$conditional_dropdowns = ( $settings && $settings['conditional_dropdowns'] ? $settings['conditional_dropdowns'] : false );
		$dependencies = array(
			'jquery',
		);
		$language = false;

		//If the almighty user decides there be no select2, then no select2 there be!
		if ( ! $disable_select2 ) {
			wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2/select2.full.min.js', array( 'jquery' ), $this->version, true );
			$dependencies[] = 'select2';
			/**
			 * So language is a thing.
			 * Let's check for polylang or WPML and add that as language for select2.
			 */
			if ( function_exists( 'pll_current_language' ) ) {
				$language = pll_current_language( 'slug' );
			} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
				$language = ICL_LANGUAGE_CODE;
			}

			/**
			 * So if we have a language, and the translation file for select2 exists,
			 * we should probably load that too.. just sayin.
			 */
			if ( $language && file_exists( plugin_dir_path( __FILE__ ) . sprintf( 'js/select2/i18n/%s.js', $language ) ) ) {
				wp_enqueue_script( 'select2-' . $language, plugin_dir_url( __FILE__ ) . sprintf( 'js/select2/i18n/%s.js', $language ), array( 'jquery', 'select2' ), $this->version, true );
			}
		}

		wp_register_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/beautiful-taxonomy-filters-public.js', $dependencies, $this->version, true );
		$localized_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'min_search' => apply_filters( 'beautiful_filters_selec2_minsearch', 8 ),
			'allow_clear' => apply_filters( 'beautiful_filters_selec2_allowclear', true ),
			'show_description' => get_option( 'beautiful_taxonomy_filters_show_description' ),
			'disable_select2' => $disable_select2,
			'conditional_dropdowns' => $conditional_dropdowns,
			'language' => apply_filters( 'beautiful_filters_language', $language ),
			'rtl' => apply_filters( 'beautiful_filters_rtl', is_rtl() ),
			'disable_fuzzy' => apply_filters( 'beautiful_filters_disable_fuzzy', false ),
		);
		//Lets make sure that if they've not chosen the placeholder option we don't allow clear since it wont do anything.
		$dropdown_behaviour = get_option('beautiful_taxonomy_filters_dropdown_behaviour');
		if(!$dropdown_behaviour || $dropdown_behaviour == 'show_all_option'){
			$localized_array['allow_clear'] = false;
		}
		wp_localize_script( $this->name, 'btf_localization', $localized_array );
		wp_enqueue_script( $this->name );


	}


	/**
	 * Output any overriding CSS by the user from plugin settings
	 *
	 * @since    1.0.0
	 */
	public function custom_css() {
		$custom_css = get_option('beautiful_taxonomy_filters_custom_css');
		if( $custom_css )
			echo '<style type="text/css">' . $custom_css . '</style>';

	}


	/**
	 * Maybe add some body classes to allow for customizations using CSS and JS.
	 */
	public function add_body_classes( $classes ) {

		$post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
		$current_post_type = btf_get_current_posttype();

		if ( ! $post_types ) {
			return $classes;
		}

		if ( post_type_exists( $current_post_type ) && in_array( $current_post_type, $post_types ) ) {
			$classes[] = 'btf-archive';
		}

		if ( is_btf_filtered() ) {
			$classes[] = 'btf-filtered';
		}

		return $classes;
	}


	/**
	* Appends the already existing GET parameters to the url.
	* This allows for custom parameters to carry on to the filtered page
	*
	* @since	1.0.0
	*/
	private function append_get_parameters($new_url){

		if(!empty($_GET)){
			$previous_parameters = $_GET;
			$i = 0;
			foreach($previous_parameters as $key => $value){
				//sanitize for safety
				$key = sanitize_text_field($key);
				$value = sanitize_text_field($value);
				//append
				$new_url .= ($i == 0 ? '?' : '&');
				$new_url .= $key . '=' . $value;
				$i++;
			}
		}
		return $new_url;

	}


	/**
	 * Returns all taxonomies that should be used for BTF with the current post type.
	 * @param  string  $current_post_type
	 * @return array/false Either an array of taxonomy slugs or false.
	 */
	private static function check_taxonomies($current_post_type){

		//Get the taxonomies of the current post type
		$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');

		//Get excluded taxonomies
		$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') );

		//Also make sure we don't try to output the builtin taxonomies since they cannot be supported
		if(is_array($excluded_taxonomies)){
			array_push($excluded_taxonomies, 'category', 'post_tag', 'post_format');
		}else{
			$excluded_taxonomies = array(
				'category',
				'post_tag',
				'post_format'
			);
		}

		//Polylang support
		if(function_exists('pll_current_language')){
			array_push($excluded_taxonomies, 'language', 'post_translations');
		}

		//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
		if( $current_taxonomies ){

			foreach( $current_taxonomies as $key => $value ){

				//If this taxonomy isn't public remove it
				if( !$value->public ){
					unset($current_taxonomies[$key]);
				}

				//If this taxonomy has been explicitly disabled by user remove it
				if( $excluded_taxonomies && in_array($key, $excluded_taxonomies) ){
					unset($current_taxonomies[$key]);
				}

			}

		}

		if( !empty($current_taxonomies) ){
			return $current_taxonomies;
		}

		//No applicable taxonomies so just bail
		return;

	}


	/**
	* Retrieves the current post type
	*
	* @since	1.1.0
	*/
	public static function get_current_posttype($rewrite = true){
		$current_post_type = get_post_type();
		if(!$current_post_type || $current_post_type == 'page'){
			global $template;
			$template_name = explode('-', basename( $template, '.php' ));
			if (in_array('archive', $template_name) && count($template_name) > 1) {
				$current_post_type = $template_name[1];
			}else{
				//didnt find the post type in the template, fall back to the wp_query!
				global $wp_query;
				if(array_key_exists('post_type', $wp_query->query) && $wp_query->query['post_type'] != ''){
					$current_post_type = $wp_query->query['post_type'];
				}
			}
		}
		if( $rewrite ){
			//Get the post type object
			$post_type_object = get_post_type_object($current_post_type);
			//Return the rewrite slug which is the one we actually want!
			return $post_type_object->rewrite['slug'];
		}else{
			return $current_post_type;
		}

	}


	/**
	 * Fetch post count for terms based on a single post type
	 *
	 * @since	1.2.8
	 */
	public static function get_term_post_count_by_type($term, $taxonomy, $post_type){

	    $args = array(
	        'fields' =>'ids',
	        'update_post_meta_cache' => false,
	        'no_found_rows' => true,
	        'posts_per_page' => 10000, // We don't set this to -1 because we don't want to crash ppls sites which have A LOT of posts
	        'post_type' => $post_type,
	        'tax_query' => array(
	            array(
	                'taxonomy' => $taxonomy,
	                'field' => 'slug',
	                'terms' => $term
	            )
	        )
	     );
	    $postcount_query = new WP_Query( $args );

	    return ( count($postcount_query->posts > 0) ? count($postcount_query->posts) : 0 );

	}

	/**
	 * Ajax function to update available term options on the fly
	 *
	 * @since	1.3.0
	 */
	public function update_filters_callback(){

		/**
		 * First of all, some security so we sleep well at night.
		 */
		$nonce = $_REQUEST['nonce'];
		if ( ! wp_verify_nonce( $nonce, 'update_btf_selects_security' ) )
			die( 'What do you think you\'re doing son?' );


		$current_taxonomy = $_REQUEST['current_taxonomy'];
		$selects = $_REQUEST['selects'];
		$post_type = $_REQUEST['posttype'];

		/**
		 * Setup basic result values
		 */
		$result = array(
			'selects' => $selects,
			'post_type' => $post_type
		);


		$args = array(
			'post_type' => $post_type,
			'posts_per_page' => 10000, // we're limiting this to 10 000 to avoid peoples servers getting into trouble.
			'no_found_rows' => true,
			'update_post_meta_cache' => false,
			'post_status' => 'publish',
			'fields' => 'ids'
		);
		if( $selects ){
			foreach( $selects as $select ){

				/**
				 * Don't query if term is not set
				 */
				if ( $select['term'] == '0' || $select['term'] == '' ) {
					continue;
				}

				/**
				 * Add this tax > term pair to the query
				 */
				$args['tax_query'][] = array(
					'taxonomy' => $select['taxonomy'],
					'field' => 'slug',
					'terms' => array($select['term'])
				);

			}
		}
		$posts_query = new WP_Query($args);

		/**
		 * Setup our base arrays
		 */
		foreach( $selects as $select ){
			if( $select['taxonomy'] === $current_taxonomy && $select['term'] != '0' )
				continue;

			$result['taxonomies'][$select['taxonomy']] = array();

		}

		/**
		 * Loop through the post results and add all available terms to the $result['taxonomies'] multidimensional array
		 * The array will look like:
		 * Array(
		 *		'taxslug' => array(
		 *	 		'termslug',
		 *			'termslug2'
		 *		),
		 *		'taxslug2' => array(
		 *	 		'termslug',
		 *			'termslug2'
		 *		),
		 * )
		 */
		if( !empty($posts_query->posts) ){
			foreach( $posts_query->posts as $post_id ){

				foreach( $selects as $select ){
					if( $select['taxonomy'] === $current_taxonomy  && $select['term'] != '0' )
						continue;

					$terms = get_the_terms($post_id, $select['taxonomy']);
					if( $terms ){
						foreach( $terms as $term ){
							if( !in_array( $term->slug, $result['taxonomies'][$select['taxonomy']] ) ){
								$result['taxonomies'][$select['taxonomy']][] = $term->slug;
							}
						}
					}
				}
			}
		}

		/**
		 * Format our result and echo back to JS
		 */
		$result = json_encode($result);
		echo $result;
		exit;

	}


	/**
	* Runs on template_include filter. Check for $POST values coming from the filter and add them to the url
	* Also check for custom GET parameters and reattach them to the url to support combination with other functionalities
	*
	* @since	1.0.0
	*/
	public function catch_filter_values(){

		//Nope, this pageload was not due to our filter!
		if ( !isset($_POST['btf_do_filtering_nonce']) || !wp_verify_nonce( $_POST['btf_do_filtering_nonce'], "Beutiful-taxonomy-filters-do-filter"))
			return;


		//If we don't have an url, this wont work!
		$referer = (isset($_POST['site-url']) ? $_POST['site-url'] : false);
		if(!$referer)
			return;

		//get current post type archive rewrite slug
		if(isset($_POST['post_type_rewrite']) && $_POST['post_type_rewrite'] != ''){
			$current_post_type_rewrite = $_POST['post_type_rewrite'];
		}else{ //If there was no post type from the form (for some reason), try to get it anyway!

			$current_post_type_rewrite = self::get_current_posttype();

		}

		//get current post type archive
		if(isset($_POST['post_type']) && $_POST['post_type'] != ''){
			$current_post_type = $_POST['post_type'];
		}else{ //If there was no post type from the form (for some reason), try to get it anyway!

			$current_post_type = self::get_current_posttype(false);

		}

		//post type validation
		if(!post_type_exists($current_post_type))
			return;

		//Polylang support
		if(function_exists('pll_current_language')){

			$language_slug = pll_current_language('slug');
			//If the post type is translated…
			if(pll_is_translated_post_type($current_post_type)){
				$new_url = pll_home_url($language_slug);
			}else{
				//base url
				$new_url = $referer . '/';
			}

		}else{

			//base url
			$new_url = $referer . '/';

		}

		$new_url .= $current_post_type_rewrite . '/';

		//Get the taxonomies of the current post type
		$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');
		if($current_taxonomies){
			foreach($current_taxonomies as $key => $value){

				//check for each taxonomy as a $_POST variable.
				//If it exists we want to append it along with the value (term) it has.
				$term = (isset($_POST['select-'.$key]) ? $_POST['select-'.$key] : false);
				if($term){
					//If the taxonomy has a rewrite slug we need to use that instead!
					if(is_array($value->rewrite) && array_key_exists('slug', $value->rewrite)){
						$new_url .= $value->rewrite['slug'] . '/' . $term . '/';
					}else{
						$new_url .= $key . '/' . $term . '/';
					}
				}

			}
		}

		//Perform actions before the redirect to the filtered page
		do_action( 'beautiful_actions_before_redirection', $current_post_type);

		//keep GET parameters
		$new_url = $this->append_get_parameters($new_url);

		//sanitize URL
		$new_url = esc_url_raw($new_url);

		//perform a redirect to the new filtered url
		wp_redirect(apply_filters( 'beautiful_filters_new_url', $new_url, $current_post_type ));
		exit;
	}


	/**
	* Attempts to automagically insert the filter on the correct archives by using the loop_start hook
	*
	* @since 1.1.1
	*/
	public function automagic_insertion($query){

		$post_types = get_option('beautiful_taxonomy_filters_post_types');

		//first make sure we're on a main query and an archive page for one of our selected posttypes
		if ( $query->is_main_query() && is_post_type_archive($post_types) && !is_feed() ) {

			$automagic = get_option('beautiful_taxonomy_filters_automagic');
			if( !$automagic )
				return;

			if( in_array('filter_info_module', $automagic) && in_array('above', $automagic) ){
				self::beautiful_filters_info();
			}

			if( in_array('filter_module', $automagic) ){
				self::beautiful_filters(false);
			}

			if( in_array('filter_info_module', $automagic) && in_array('below', $automagic) ){
				self::beautiful_filters_info();
			}
		}

	}


	/**
	 * Modifies the select elements belonging to BTF.
	 * Mostly just adding data parameters to use with our JS functions.
	 *
	 * @since	1.3
	 */
	public function modify_select_elements( $select, $parameters ){

		/**
		 * If there's no class at all just return, not our stuff!
		 */
		if( !isset($parameters['class']) || $parameters['class'] == '' )
			return $select;

		/**
		 * So there's atleast one class.. if none is ours this is still not our stuff!
		 */
		$classes = explode( ' ', $parameters['class'] );
		if( !in_array( 'beautiful-taxonomy-filters-select', $classes) )
			return $select;

		/**
		 * Save our entire wp_dropdown_categories arguments array as a serialized value.
		 */
		$save_parameters = $parameters;
		if( isset( $save_parameters['walker'] ) ){
			unset($save_parameters['walker']);
		}
		$new_select = str_replace( '<select', '<select data-taxonomy="' . $parameters['taxonomy'] . '" data-options="' . htmlspecialchars( json_encode( $save_parameters ) ) . '" data-nonce="' . wp_create_nonce('update_btf_selects_security') . '"', $select );

		return $new_select;
	}


	/**
	* Public function to return the filters for the current post type archive.
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters( $post_type ) {
		//Fetch the plugins options
		//Apply filters on them to let users modify the options before they're being used!
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );

		//If there's no post types, bail early!
		if(!$post_types)
			return;


		//get current post type archive
		if($post_type){

			$current_post_type = $post_type;
			//Get the post type object
			$post_type_object = get_post_type_object($current_post_type);
			//Take the rewrite slug which is the one we actually want!
			$current_post_type_rewrite = $post_type_object->rewrite['slug'];

		}elseif(isset($_POST['post_type']) && $_POST['post_type'] != ''){

			$current_post_type = $_POST['post_type'];
			//Get the post type object
			$post_type_object = get_post_type_object($current_post_type);
			//Take the rewrite slug which is the one we actually want!
			$current_post_type_rewrite = $post_type_object->rewrite['slug'];

		}else{ //If there was no post type from the form (for some reason) and there is no post type supplied by the public function, try to get it anyway!

			$current_post_type = self::get_current_posttype(false);
			$current_post_type_rewrite = self::get_current_posttype();

		}

		//not a post type we have the filter on or perhaps not even a registered post type, bail early!
		if(!post_type_exists($current_post_type) || !in_array($current_post_type, $post_types))
			return;

		//do some taxonomy checking will ya
		$current_taxonomies = self::check_taxonomies($current_post_type);

		//On a post type that we want the filter on, and we have atleast one valid taxonomy
		if(in_array($current_post_type, $post_types) && !empty($current_taxonomies)){

			require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-display.php';

		}

	}

	/**
	* Public function to return information about the currently active filters
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters_info(){

		global $wp_query;
		$current_taxonomies = $wp_query->tax_query->queries;
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );
		$current_post_type = self::get_current_posttype(false);

		//If there is no current post type, bail early!
		if(!post_type_exists($current_post_type) || !in_array($current_post_type, $post_types))
			return;

		require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-info-display.php';

	}

}
