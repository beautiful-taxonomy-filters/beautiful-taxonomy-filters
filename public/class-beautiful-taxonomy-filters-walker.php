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

    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
    	global $wp_query;
		$queryvars = $wp_query->query_vars;	
        $cat_name = apply_filters('list_cats', $category->name, $category);
        $output .= "\t<option class=\"level-$depth\" value=\"".$category->slug."\"";
        if(isset($_GET)){
        	foreach($_GET as $get_variable){
	        	if(strpos($get_variable,',') !== false){
		        	$get_array = explode(',', $get_variable);
	        	}else{
		        	$get_array[] = $get_variable;
	        	}
	        	foreach($get_array as $get_single){
		        	if ( $category->term_id == $args['selected'] || $get_single == $category->slug ){
						$output .= ' selected="selected"';
					}
	        	}
        	}
		}
		if ( in_array($category->slug, $queryvars, true)  ) { 
			$output .= 'selected="selected" ';
		}
        $output .= '>';
        $output .= $cat_name;
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. $category->count .')';
        if (isset ( $args['show_last_update'] ) ) {
            $format = 'Y-m-d';
            $output .= '&nbsp;&nbsp;' . gmdate($format, $category->last_update_timestamp);
        }
        $output .= "</option>\n";
	}
}
?>