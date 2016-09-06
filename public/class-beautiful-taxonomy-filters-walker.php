<?php
/**
 * The Custom Walker class being used by our wp_dropdown_categories to render the filter dropdowns
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 */

/**
 * The Custom Walker class being used by our wp_dropdown_categories to render the filter dropdowns
 *
 * Changes the usual term ID in the option value to being the slug instead
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */
class Walker_Slug_Value_Category_Dropdown extends Walker_CategoryDropdown {


	private $call_type;
	private $instance;
	private $show_description;
	private $disable_select2;
	private $post_type;

    function __construct($call_type = '', $instance = false) {

        $this->call_type = $call_type;
        $this->instance = $instance;

        $this->init_settings();

    }

	/**
	 * Initial settings for the walker
	 * Find out wether they want to show descriptions or not
	 *
	 * @since 1.2.0
	 */
	private function init_settings(){

		/**
		 * If this walker gets called from a widget we need to fetch that widgets settings and apply filters
		 * Otherwise just get the settings from wp_options
		 */
		if( $this->call_type == 'widget' ){

			$this->post_type = ( $this->instance['post_type'] != 'automatic' ? $this->instance['post_type'] : Beautiful_Taxonomy_Filters_Public::get_current_posttype(false) );

			if( isset($this->instance['show_description']) ){
				$this->show_description = strip_tags($this->instance['show_description']);
				if($this->show_description == 'inherit'){
			    	$this->show_description = apply_filters( 'beautiful_filters_show_description', get_option('beautiful_taxonomy_filters_show_description'), $this->post_type );
		    	}else{
			    	$this->show_description = ($this->show_description == 'enable' ? 1 : 0);
			    	$this->show_description = apply_filters( 'beautiful_filters_show_description', $this->show_description, $this->post_type );
		    	}
			}else{
				$this->show_description = false;
			}

		}else{

			$this->post_type = Beautiful_Taxonomy_Filters_Public::get_current_posttype(false);
			$this->show_description = apply_filters( 'beautiful_filters_show_description', get_option('beautiful_taxonomy_filters_show_description'), $this->post_type );
		}

		$this->disable_select2 = (get_option('beautiful_taxonomy_filters_disable_select2') ? get_option('beautiful_taxonomy_filters_disable_select2') : false);

	}



	/**
     * Start the element output.
     *
     * @see Walker::start_el()
     * @since 1.0.0
     *
     * @param string $output   Passed by reference. Used to append additional content.
     * @param object $category Category data object.
     * @param int    $depth    Depth of category. Used for padding.
     * @param array  $args     Uses 'selected', 'show_count', and 'value_field' keys, if they exist.
     *                         See {@see wp_dropdown_categories()}.
     */
    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {

    	global $wp_query;
		$queryvars = $wp_query->query_vars;
        $cat_name = apply_filters('list_cats', $category->name, $category);
        $output .= "\t" . '<option class="level-' . $depth . ' ' . $category->slug . '" value="' . $category->slug . '"';
        if(isset($_GET)){
        	foreach($_GET as $get_variable){
	        	if(strpos($get_variable,',') !== false){
		        	$get_array = explode(',', $get_variable);
	        	}else{
		        	$get_array[] = $get_variable;
	        	}
	        	foreach($get_array as $get_single){
		        	if ( $category->term_id == $args['selected'] || $get_single == $category->slug ){
						$output .= ' selected="selected" ';
					}
	        	}
        	}
		}
		if ( in_array($category->slug, $queryvars, true)  ) {
			$output .= ' selected="selected" ';
		}
        $output .= '>';

        //run our custom filter
        $output .= apply_filters( 'beautiful_filters_term_name', $cat_name, $category, $depth );

        if ( $args['show_count'] ){
        	//If they want a post count make sure to only show the count for this specific post type
        	$count = Beautiful_Taxonomy_Filters_Public::get_term_post_count_by_type($category->slug, $category->taxonomy, $this->post_type);
            $output .= '&nbsp;&nbsp;(' . $count . ')';
        }

        if (isset ( $args['show_last_update'] ) ) {
            $format = 'Y-m-d';
            $output .= '&nbsp;&nbsp;' . gmdate($format, $category->last_update_timestamp);
        }


        if( $this->show_description && $category->description ){
	        //If we dont want select2 we cant put anything fancy around the description so this will have to do.
	        if( $this->disable_select2 ){
		        $output .= ' – ' . $category->description;
	        } else {
		        //We just use an unlikely textstring so we can target it with JS later.
		        $output .= ':.:' . $category->description . ':-:';
	        }

        }

        $output .= "</option>\n";

	}
}
?>