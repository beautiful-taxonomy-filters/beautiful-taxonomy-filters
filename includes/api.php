<?php
/**
 * This file contains all publicly accessible API functions used in and for BTF.
 * All of them are pluggable and can be replaced with your own.
 */


/**
 * Check wether the current page is filtered by BTF.
 * @param  string  $current_post_type A post type supplied to check against.
 * @return true/false
 */
function is_btf_filtered() {

	$taxonomies = btf_get_current_taxonomies();
	if ( ! $taxonomies ) {
		return false;
	}

	global $wp_query;
	if ( $wp_query->query ) {
		foreach ( $wp_query->query as $key => $value ) {
			if ( array_key_exists( $key, $taxonomies ) && '' != $value ) {
				return true;
			}
		}
	}

	return false;

}



/**
* Retrieves the current post type
*
* @since	1.1.0
*/
function btf_get_current_posttype() {
	$current_post_type = get_post_type();
	if ( ! $current_post_type || 'page' == $current_post_type ) {
		global $template;
		$template_name = explode( '-', basename( $template, '.php' ) );
		if ( in_array( 'archive', $template_name ) && 1 < count( $template_name ) ) {
			$current_post_type = $template_name[1];
		} else {
			//didnt find the post type in the template, fall back to the wp_query!
			global $wp_query;
			if ( array_key_exists( 'post_type', $wp_query->query ) && '' != $wp_query->query['post_type'] ) {
				$current_post_type = $wp_query->query['post_type'];
			}
		}
	}

	$btf_post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
	if ( $btf_post_types && in_array( $current_post_type, $btf_post_types ) ) {
		return $current_post_type;
	}

	return false;
}


/**
 * Returns all taxonomies that should be used for BTF with the current post type.
 * @param  string  $current_post_type
 * @return array/false Either an array of taxonomy slugs or false.
 */
function btf_get_current_taxonomies( $current_post_type = false ) {

	if ( ! $current_post_type ) {
		$current_post_type = btf_get_current_posttype();
	}

	if ( ! $current_post_type ) {
		return false;
	}

	//Get the taxonomies of the current post type
	$current_taxonomies = get_object_taxonomies( $current_post_type, 'objects' );

	//Get excluded taxonomies
	$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option( 'beautiful_taxonomy_filters_taxonomies' ) );

	//Also make sure we don't try to output the builtin taxonomies since they cannot be supported
	if ( is_array( $excluded_taxonomies ) ) {
		array_push( $excluded_taxonomies, 'category', 'post_tag', 'post_format' );
	} else {
		$excluded_taxonomies = array(
			'category',
			'post_tag',
			'post_format',
		);
	}

	//Polylang support
	if ( function_exists( 'pll_current_language' ) ) {
		array_push( $excluded_taxonomies, 'language', 'post_translations' );
	}

	//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
	if ( $current_taxonomies ) {

		foreach ( $current_taxonomies as $key => $value ) {

			//If this taxonomy isn't public remove it
			if ( ! $value->public ) {
				unset( $current_taxonomies[ $key ] );
			}

			//If this taxonomy has been explicitly disabled by user remove it
			if ( $excluded_taxonomies && in_array( $key, $excluded_taxonomies ) ) {
				unset( $current_taxonomies[ $key ] );
			}
		}
	}

	if ( ! empty( $current_taxonomies ) ) {
		return $current_taxonomies;
	}

	return false;

}
