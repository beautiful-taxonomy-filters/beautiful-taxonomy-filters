<?php

/**
 * Generates all the rewrite rules for a given post type.
 *
 *
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
 * @author Jonathan de Jong <me@jonte.dev>
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
	 * @param array $args optional arguments. For example used to create polylang support.
	 * @since    1.0.0
	 */
	public function generate_rewrite_rules( $post_type, $args = array() ) {
		global $wp_rewrite;

		if ( ! is_object( $post_type ) ) {
			$post_type = get_post_type_object( $post_type );
		}

		// Get the post type permalink slug. The has_archive value takes precedence if it's been set to a string.
		$post_type_slug = ( is_string( $post_type->has_archive ) ) ? $post_type->has_archive : $post_type->rewrite['slug'];
		$new_rewrite_rules = array();
		$taxonomies        = btf_get_current_taxonomies( $post_type->name );

		// dont do anything if there are no taxonomies!
		if ( empty( $taxonomies ) ) {
			return;
		}

		// Setup rewrite rules!
		$new_rewrite_rule = $post_type_slug;
		$new_query_string = 'index.php?post_type=' . $post_type->name;

		$n = 1;
		foreach ( $taxonomies as $taxonomy ) {
			// Loop through each taxonomy and add it to our rewrite.
			$query_var = $taxonomy->query_var;
			$rewrite_slug = ( ! empty( $taxonomy->rewrite['slug'] ) ) ? $taxonomy->rewrite['slug'] : $query_var;

			$new_rewrite_rule .= sprintf( '(?:/%s/([^/]+))?', $rewrite_slug );
			$new_query_string .= sprintf( '&%s=%s', $query_var, $wp_rewrite->preg_index( $n ) );

			$n++;
		}

		// Add pagination support.
		$new_paged_rewrite_rule = $new_rewrite_rule . '/page/([0-9]{1,})';
		$new_paged_query_string = $new_query_string . '&paged=' . $wp_rewrite->preg_index( $n );

		// Make the trailing backslash optional.
		$new_paged_rewrite_rule = $new_paged_rewrite_rule . '/?$';
		$new_rewrite_rule = $new_rewrite_rule . '/?$';

		$new_rewrite_rules = array(
			$new_paged_rewrite_rule => $new_paged_query_string,
			$new_rewrite_rule => $new_query_string,
		);

		return $new_rewrite_rules;
	}

}
