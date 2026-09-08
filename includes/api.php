<?php
/**
 * This file contains all publicly accessible API functions used in and for BTF.
 * All of them are pluggable and can be replaced with your own.
 */


/**
 * Check wether the current page is filtered by BTF.
 * @return boolean
 */
function is_btf_filtered() {

	$taxonomies = btf_get_current_taxonomies();
	if ( ! $taxonomies ) {
		return false;
	}

	// Convert $taxonomies to simpler check keys based on their query_var parameter.
	// Do this because the key in $taxonomies is not necessarily the same as the one used by WP_Query. query_var is!
	$check_taxonomies = array();
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy = array_filter(
				(array) $taxonomy, function( $taxonomy_key ) {
						return ( 'query_var' == $taxonomy_key );
				}, ARRAY_FILTER_USE_KEY
			);
			$check_taxonomies[ $taxonomy['query_var'] ] = 1;
		}
	}

	global $wp_query;
	if ( $wp_query->query ) {
		foreach ( $wp_query->query as $key => $value ) {

			if ( array_key_exists( $key, $check_taxonomies ) && '' != $value ) {
				return true;
			}
		}
	}

	return false;

}



/**
 * Resolves a post type name from the archive template currently being rendered.
 *
 * WordPress names a post type archive template archive-{post_type}.php and the post
 * type name may itself contain hyphens, like wp-book rendered by archive-wp-book.php.
 * That's why we strip the known prefix instead of exploding on every hyphen, which
 * would only ever give us the part before the first one.
 *
 * @since  2.6.0
 * @return string/false The post type name or false if the template isn't a post type archive.
 */
function btf_get_post_type_from_template() {

	global $template;

	if ( empty( $template ) || ! is_string( $template ) ) {
		return false;
	}

	$basename = basename( $template, '.php' );

	//Only an archive-{post_type}.php template tells us anything about the post type.
	if ( 0 !== strpos( $basename, 'archive-' ) ) {
		return false;
	}

	$post_type = substr( $basename, strlen( 'archive-' ) );

	//Make sure we never hand a garbage value back to get_post_type_object() and friends.
	if ( empty( $post_type ) || ! post_type_exists( $post_type ) ) {
		return false;
	}

	return $post_type;

}


/**
* Retrieves the current post type
*
* @since    1.1.0
*/
function btf_get_current_posttype() {
	$current_post_type = get_post_type();
	if ( ! $current_post_type || 'page' == $current_post_type ) {
		$post_type_from_template = btf_get_post_type_from_template();
		if ( $post_type_from_template ) {
			$current_post_type = $post_type_from_template;
		} else {
			//didnt find the post type in the template, fall back to the wp_query!
			global $wp_query;
			if ( isset( $wp_query->query ) && array_key_exists( 'post_type', $wp_query->query ) && ! empty( $wp_query->query['post_type'] ) ) {
				$current_post_type = $wp_query->query['post_type'];
			}
		}
	}

	$btf_post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
	if ( is_array( $btf_post_types ) && in_array( $current_post_type, $btf_post_types ) ) {
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


/**
 * Returns the slug a post type uses for its archive URL.
 *
 * This is the same value WordPress puts into get_post_type_archive_link(): the
 * has_archive value takes precedence when it's been set to a string, otherwise
 * the rewrite slug is used.
 *
 * The resolved slug is passed through the `beautiful_filters_post_type_archive_slug`
 * filter, the counterpart to `beautiful_filters_taxonomy_rewrite_slug`. Both the
 * filtered url and the rewrite rules are built from this function, so a filter here
 * changes both of them together. Bear in mind that the rewrite rules are generated
 * once and then cached in the rewrite_rules option while the url is built on every
 * request, so returning a value that varies with request state (the current language
 * being the obvious example) will make the two disagree and the url won't resolve.
 *
 * @since  2.5.0
 * @param  string|object $post_type Post type name or post type object.
 * @return string/false The archive slug or false if it couldn't be resolved.
 *
 * @filter beautiful_filters_post_type_archive_slug ( $post_type_slug, $post_type_name ) @since 2.6.0
 */
function btf_get_post_type_archive_slug( $post_type ) {

	if ( ! is_object( $post_type ) ) {
		$post_type = get_post_type_object( $post_type );
	}

	if ( ! is_object( $post_type ) ) {
		return false;
	}

	if ( is_string( $post_type->has_archive ) && '' !== $post_type->has_archive ) {
		$post_type_slug = $post_type->has_archive;
	} elseif ( is_array( $post_type->rewrite ) && ! empty( $post_type->rewrite['slug'] ) ) {
		$post_type_slug = $post_type->rewrite['slug'];
	} else {
		$post_type_slug = $post_type->name;
	}

	$post_type_slug = trim( $post_type_slug, '/' );

	return apply_filters( 'beautiful_filters_post_type_archive_slug', $post_type_slug, $post_type->name );

}


/**
 * Returns the url segment to use for a taxonomy when building filtered urls.
 *
 * Both the filtered url (public) and the rewrite rules (admin) are built from this
 * so they always stay in sync. If they don't, the pretty url won't resolve!
 *
 * Taxonomies are often registered with their rewrite slug nested underneath the post
 * type archive, for example 'horses/locations' on a post type archived at 'horses'.
 * Since we always start the url at the post type archive that prefix would end up in
 * the url twice, so we strip it here.
 *
 * @since  2.5.0
 * @param  object $taxonomy       A taxonomy object.
 * @param  string $post_type_slug The archive slug of the post type being filtered.
 * @return string The url segment for the taxonomy.
 */
function btf_get_taxonomy_rewrite_slug( $taxonomy, $post_type_slug = '' ) {

	if ( is_array( $taxonomy->rewrite ) && ! empty( $taxonomy->rewrite['slug'] ) ) {
		$rewrite_slug = $taxonomy->rewrite['slug'];
	} elseif ( ! empty( $taxonomy->query_var ) ) {
		$rewrite_slug = $taxonomy->query_var;
	} else {
		$rewrite_slug = $taxonomy->name;
	}

	$rewrite_slug = trim( $rewrite_slug, '/' );

	// Remove the post type archive slug if the taxonomy is nested underneath it.
	// Note the trailing slash, we only want to remove it when there's an actual
	// taxonomy segment left afterwards.
	if ( $post_type_slug ) {
		$prefix = trailingslashit( $post_type_slug );
		if ( 0 === strpos( $rewrite_slug, $prefix ) ) {
			$rewrite_slug = substr( $rewrite_slug, strlen( $prefix ) );
		}
	}

	return apply_filters( 'beautiful_filters_taxonomy_rewrite_slug', $rewrite_slug, $taxonomy->name, $post_type_slug );

}


/**
 * Returns the label to use as the "all terms" option for a taxonomy.
 *
 * WordPress fills unspecified taxonomy labels in a chain: all_items falls back to
 * menu_name and menu_name falls back to name. A taxonomy registered without an
 * explicit all_items label therefore just hands us its own name back, which makes
 * the filter info module print things like "Meal Types: Meal Types" and gives the
 * dropdowns an "all" option reading just "Meal Types". When we detect that fallback
 * we build a proper label instead. An all_items that was actually set is returned
 * untouched.
 *
 * @since  2.6.0
 * @param  string|object $taxonomy Taxonomy name or taxonomy object.
 * @return string The all items label, or an empty string if it couldn't be resolved.
 */
function btf_get_taxonomy_all_items_label( $taxonomy ) {

	if ( ! is_object( $taxonomy ) ) {
		$taxonomy = get_taxonomy( $taxonomy );
	}

	if ( ! is_object( $taxonomy ) || ! isset( $taxonomy->labels ) ) {
		return '';
	}

	$labels = $taxonomy->labels;

	$name      = isset( $labels->name ) ? $labels->name : '';
	$menu_name = isset( $labels->menu_name ) ? $labels->menu_name : '';
	$all_items = isset( $labels->all_items ) ? $labels->all_items : '';

	// Both comparisons are needed. menu_name defaults to name, but a developer who
	// sets only menu_name gets an all_items matching menu_name and not name.
	if ( '' === $all_items || $all_items === $name || $all_items === $menu_name ) {
		if ( '' === $name ) {
			$label = '';
		} else {
			// Translators: %s is the name of a taxonomy, for example "Meal Types".
			$label = sprintf( __( 'All %s', 'beautiful-taxonomy-filters' ), $name );
		}
	} else {
		$label = $all_items;
	}

	return apply_filters( 'beautiful_filters_taxonomy_all_items_label', $label, $taxonomy->name );

}
