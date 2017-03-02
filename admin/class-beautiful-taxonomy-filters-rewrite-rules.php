<?php

/**
 * Generates all the rewrite rules for a given post type.
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 *
 * The rewrite rules allow a post type to be filtered by all possible combinations & permutations
 * of taxonomies that apply to the specified post type and additional query_vars specified with
 * the $query_vars parameter.
 *
 * Must be called from a function hooked to the 'generate_rewrite_rules' action so that the global
 * $wp_rewrite->preg_index function returns the correct value.
 *
 * @param string|object $post_type The post type for which you wish to create the rewrite rules
 * @param array $query_vars optional Non-taxonomy query vars you wish to create rewrite rules for. Rules will be created to capture any single string for the query_var, that is, a rule of the form '/query_var/(.+)/'
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin
 * @author Brent Shepherd <me@brentshepherd.com>
 * @contributor Jonathan de Jong <jonathan@tigerton.se>
 * @since 1.0
 */

class Beautiful_Taxonomy_Filters_Rewrite_Rules {

	/**
	 * Generates all the rewrite rules for a given post type.
	 *
	 * The rewrite rules allow a post type to be filtered by all possible combinations & permutations
	 * of taxonomies that apply to the specified post type and additional query_vars specified with
	 * the $query_vars parameter.
	 * @param string|object $post_type The post type for which you wish to create the rewrite rules
	 * @param array $query_vars optional Non-taxonomy query vars you wish to create rewrite rules for. Rules will be created to capture any single string for the query_var, that is, a rule of the form '/query_var/(.+)/'
	 * @since    1.0.0
	 */
	public function generate_rewrite_rules( $post_type, $excluded_taxonomies = array(), $query_vars = array() ) {

	    global $wp_rewrite;

	    if ( ! is_object( $post_type ) ) {
			$post_type = get_post_type_object( $post_type );
		}

	    $new_rewrite_rules = array();
	    $taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

	    // Add taxonomy filters to the query vars array
	    foreach ( $taxonomies as $taxonomy ) {
		    if ( ! empty( $excluded_taxonomies ) ) {
			    if ( '' != $taxonomy->rewrite['slug'] ) {
				    if ( ! in_array( $taxonomy->rewrite['slug'], $excluded_taxonomies ) ) {
					    $query_vars[] = $taxonomy->query_var;
					}
			    } else {
				    if ( ! in_array( $taxonomy->query_var, $excluded_taxonomies ) ) {
					    $query_vars[] = $taxonomy->query_var;
					}
			    }
		    } else {

				$query_vars[] = $taxonomy->query_var;

		    }
	    }
	    // Loop over all the possible combinations of the query vars
	    for ( $i = 1; $i <= count( $query_vars );  $i++ ) {

			$new_rewrite_rule = $post_type->rewrite['slug'] . '/';
			$new_query_string = 'index.php?post_type=' . $post_type->name;

	        // Prepend the rewrites & queries
	        for ( $n = 1; $n <= $i; $n++ ) {
	            $new_rewrite_rule .= '(' . implode( '|', $query_vars ) . ')/(.+?)/';
	            $new_query_string .= '&' . $wp_rewrite->preg_index( $n * 2 - 1 ) . '=' . $wp_rewrite->preg_index( $n * 2 );
	        }

	        // Allow paging of filtered post type - WordPress expects 'page' in the URL but uses 'paged' in the query string so paging doesn't fit into our regex
	        $new_paged_rewrite_rule = $new_rewrite_rule . 'page/([0-9]{1,})/';
	        $new_paged_query_string = $new_query_string . '&paged=' . $wp_rewrite->preg_index( $i * 2 + 1 );

	        // Make the trailing backslash optional
	        $new_paged_rewrite_rule = $new_paged_rewrite_rule . '?$';
	        $new_rewrite_rule = $new_rewrite_rule . '?$';

	        // Add the new rewrites
	        $new_rewrite_rules = array(
	        	$new_paged_rewrite_rule => $new_paged_query_string,
	            $new_rewrite_rule => $new_query_string,
	        ) + $new_rewrite_rules;
	    }

		// Also add rewrite rules to handle query_var != rewrite slug.
		$new_rewrite_rule = $post_type->rewrite['slug'];
		$new_query_string = 'index.php?post_type=' . $post_type->name;

		$n = 1;
		foreach ( $taxonomies as $taxonomy ) {

			$qv = $taxonomy->query_var;
			$rw = ( '' != $taxonomy->rewrite['slug'] ? $taxonomy->rewrite['slug'] : $qv );

			// Skip tax if excluded
			if ( ! empty( $excluded_taxonomies ) && in_array( $rw, $excluded_taxonomies ) ) {
				continue;
			}

			$new_rewrite_rule .= sprintf( '(?:/%s/([^/]+))?', $rw );
			$new_query_string .= sprintf( '&%s=%s', $qv, $wp_rewrite->preg_index( $n ) );

			$n++;
		}

		// Add paging.
		$new_paged_rewrite_rule = $new_rewrite_rule . 'page/([0-9]{1,})';
		$new_paged_query_string = $new_query_string . '&paged=' . $wp_rewrite->preg_index( $n );

		// Make the trailing backslash optional.
		$new_paged_rewrite_rule = $new_paged_rewrite_rule . '/?$';
		$new_rewrite_rule = $new_rewrite_rule . '/?$';

		$new_rewrite_rules = array(
			$new_paged_rewrite_rule => $new_paged_query_string,
			$new_rewrite_rule => $new_query_string,
		) + $new_rewrite_rules;

	    return $new_rewrite_rules;

	}

}
