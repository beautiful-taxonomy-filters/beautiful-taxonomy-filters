<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 *
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Jonathan de Jong <me@jonte.dev>
 */

class Beautiful_Taxonomy_Filters_Public {

	/**
	 * Handle of the filter module script.
	 *
	 * @since 2.6.1
	 */
	const SCRIPT_HANDLE = 'beautiful-taxonomy-filters';

	/**
	 * Whether a filter module was rendered before the scripts were registered.
	 *
	 * @since 2.6.1
	 * @var bool
	 */
	private static $scripts_requested = false;

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

		$this->name    = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$disable_select2 = ( get_option( 'beautiful_taxonomy_filters_disable_select2' ) ? get_option( 'beautiful_taxonomy_filters_disable_select2' ) : false );

		if ( ! $disable_select2 ) {
			//the basic stylesheet that should always be loaded! For select2 to display properly
			wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		}

		//BTFs own basic stylesheet. Should also always be loaded (very minimal)
		wp_enqueue_style( $this->name . '-basic', plugin_dir_url( __FILE__ ) . 'css/beautiful-taxonomy-filters-base.min.css', array(), $this->version, 'all' );

		//Get user selected style
		$selected_style = get_option( 'beautiful_taxonomy_filters_styles' );
		switch ( $selected_style ) {
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
		$disable_select2       = ( get_option( 'beautiful_taxonomy_filters_disable_select2' ) ? get_option( 'beautiful_taxonomy_filters_disable_select2' ) : false );
		$settings              = ( get_option( 'beautiful_taxonomy_filters_settings' ) ? get_option( 'beautiful_taxonomy_filters_settings' ) : false );
		$conditional_dropdowns = ( $settings && $settings['conditional_dropdowns'] ? $settings['conditional_dropdowns'] : false );
		$dependencies          = array(
			'jquery',
		);
		$language              = false;

		//Scripts are only registered here. They're enqueued by enqueue_module_scripts() when a filter
		//module is actually rendered, so pages without one don't load them. Deferred and in the
		//footer. WordPress before 6.3 reads the array as $in_footer = true and ignores the strategy.
		$script_args = array(
			'in_footer' => true,
			'strategy'  => 'defer',
		);

		//If the almighty user decides there be no select2, then no select2 there be!
		if ( ! $disable_select2 ) {
			wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2/select2.full.min.js', array( 'jquery' ), $this->version, $script_args );
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
				wp_register_script( 'select2-' . $language, plugin_dir_url( __FILE__ ) . sprintf( 'js/select2/i18n/%s.js', $language ), array( 'jquery', 'select2' ), $this->version, $script_args );
				$dependencies[] = 'select2-' . $language;
			}
		}

		wp_register_script( self::SCRIPT_HANDLE, plugin_dir_url( __FILE__ ) . 'js/beautiful-taxonomy-filters-public.js', $dependencies, $this->version, $script_args );
		$localized_array = array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'min_search'            => apply_filters( 'beautiful_filters_selec2_minsearch', 8 ),
			'allow_clear'           => apply_filters( 'beautiful_filters_selec2_allowclear', true ),
			'show_description'      => get_option( 'beautiful_taxonomy_filters_show_description' ),
			'disable_select2'       => $disable_select2,
			'conditional_dropdowns' => $conditional_dropdowns,
			'language'              => apply_filters( 'beautiful_filters_language', $language ),
			'rtl'                   => apply_filters( 'beautiful_filters_rtl', is_rtl() ),
			'disable_fuzzy'         => apply_filters( 'beautiful_filters_disable_fuzzy', false ),
			'show_count'            => apply_filters( 'beautiful_filters_show_empty', get_option( 'beautiful_taxonomy_filters_show_count' ) ),
		);
		//Lets make sure that if they've not chosen the placeholder option we don't allow clear since it wont do anything.
		$dropdown_behaviour = get_option( 'beautiful_taxonomy_filters_dropdown_behaviour' );
		if ( ! $dropdown_behaviour || $dropdown_behaviour == 'show_all_option' ) {
			$localized_array['allow_clear'] = false;
		}
		wp_localize_script( self::SCRIPT_HANDLE, 'btf_localization', $localized_array );

		//A module rendered before this ran (block themes render the template before wp_head).
		if ( self::$scripts_requested ) {
			wp_enqueue_script( self::SCRIPT_HANDLE );
		}

	}

	/**
	 * Enqueues the scripts of the filter module, select2 and its translation included through
	 * the dependencies. Called whenever a filter module is rendered: by beautiful_filters() for the
	 * shortcode, the show_beautiful_filters action and automagic, and by the filter widget.
	 * Scripts enqueued while the page renders are printed in the footer. Block themes render the
	 * template before wp_enqueue_scripts runs, so if the scripts aren't registered yet the request
	 * is remembered and enqueue_scripts() picks it up.
	 *
	 * Enqueue the beautiful-taxonomy-filters script handle yourself if you build a form without
	 * any of those.
	 *
	 * @since 2.6.1
	 */
	public static function enqueue_module_scripts() {
		if ( wp_script_is( self::SCRIPT_HANDLE, 'registered' ) ) {
			wp_enqueue_script( self::SCRIPT_HANDLE );
		} else {
			self::$scripts_requested = true;
		}
	}


	/**
	 * Output any overriding CSS by the user from plugin settings
	 *
	 * @since    1.0.0
	 */
	public function custom_css() {
		$custom_css = get_option( 'beautiful_taxonomy_filters_custom_css' );
		if ( $custom_css ) {
			echo '<style type="text/css">' . wp_strip_all_tags( $custom_css ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

	}


	/**
	 * Maybe add some body classes to allow for customizations using CSS and JS.
	 */
	public function add_body_classes( $classes ) {

		$post_types        = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
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
	* @since    1.0.0
	*/
	private function append_get_parameters( $new_url ) {
		$parameters = array();
		foreach ( $_GET as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			// Only plain key=value pairs are carried over. Keys and values are url encoded so a
			// value can't smuggle extra parameters or a fragment into the url.
			if ( ! is_scalar( $value ) ) {
				continue;
			}
			$key = sanitize_text_field( (string) $key );
			if ( '' === $key ) {
				continue;
			}
			$parameters[ rawurlencode( $key ) ] = rawurlencode( sanitize_text_field( wp_unslash( (string) $value ) ) );
		}
		return $parameters ? add_query_arg( $parameters, $new_url ) : $new_url;

	}


	/**
	* Retrieves the current post type
	*
	* @since    1.1.0
	*/
	public static function get_current_posttype( $rewrite = true ) {
		$current_post_type = get_post_type();
		if ( ! $current_post_type || $current_post_type == 'page' ) {
			$post_type_from_template = btf_get_post_type_from_template();
			if ( $post_type_from_template ) {
				$current_post_type = $post_type_from_template;
			} else {
				//didnt find the post type in the template, fall back to the wp_query!
				global $wp_query;
				if ( isset( $wp_query->query ) && array_key_exists( 'post_type', $wp_query->query ) && $wp_query->query['post_type'] != '' ) {
					$current_post_type = $wp_query->query['post_type'];
				}
			}
		}
		if ( $rewrite ) {
			//Return the slug the post type uses for its archive url, which is the one we actually want!
			//Resolved the same way as the rewrite rules and the filtered url, and false when it can't be resolved.
			return btf_get_post_type_archive_slug( $current_post_type );
		} else {
			return $current_post_type;
		}

	}


	/**
	 * Fetch post count for terms based on a single post type
	 *
	 * @since   1.2.8
	 */
	public static function get_term_post_count_by_type( $term, $taxonomy, $post_type ) {

		$args            = array(
			'fields'                 => 'ids',
			'update_post_meta_cache' => false,
			'no_found_rows'          => true,
			'posts_per_page'         => 10000, // We don't set this to -1 because we don't want to crash ppls sites which have A LOT of posts
			'post_type'              => $post_type,
			'tax_query'              => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $term,
				),
			),
		);
		$args            = apply_filters( 'beautiful_filters_post_count_args', $args );
		$postcount_query = new WP_Query( $args );

		return ( ! empty( $postcount_query->posts ) && count( $postcount_query->posts ) ? count( $postcount_query->posts ) : 0 );

	}

	/**
	 * Ajax function to update available term options on the fly
	 *
	 * @since   1.3.0
	 */
	public function update_filters_callback() {

		// Security check
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		if ( ! wp_verify_nonce( $nonce, 'update_btf_selects_security' ) ) {
			wp_send_json_error( array( 'message' => __( 'Security check failed.', 'beautiful-taxonomy-filters' ) ), 403 );
		}

		// The endpoint only exists for the conditional dropdowns, so don't answer when they're off.
		$settings = get_option( 'beautiful_taxonomy_filters_settings' );
		if ( ! is_array( $settings ) || empty( $settings['conditional_dropdowns'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Conditional dropdowns are disabled.', 'beautiful-taxonomy-filters' ) ), 403 );
		}

		// Only answer for a post type the filter is enabled on, and only about the taxonomies the
		// filter module itself would show for it (public, not excluded, not built in). Anything
		// else would let a visitor read terms the site never meant to expose.
		$post_type  = isset( $_REQUEST['posttype'] ) && is_scalar( $_REQUEST['posttype'] ) ? sanitize_key( wp_unslash( $_REQUEST['posttype'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
		if ( ! $post_type || ! post_type_exists( $post_type ) || ! is_array( $post_types ) || ! in_array( $post_type, $post_types, true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid post type.', 'beautiful-taxonomy-filters' ) ), 400 );
		}

		$allowed_taxonomies = btf_get_current_taxonomies( $post_type );
		$allowed_taxonomies = $allowed_taxonomies ? array_keys( $allowed_taxonomies ) : array();

		// Requested taxonomies are matched exactly against that list, so whatever survives is a
		// registered taxonomy name. No taxonomies requested means all of them.
		$taxonomies = array();
		if ( isset( $_REQUEST['taxonomies'] ) && is_array( $_REQUEST['taxonomies'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			foreach ( wp_unslash( $_REQUEST['taxonomies'] ) as $taxonomy ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( is_string( $taxonomy ) && in_array( $taxonomy, $allowed_taxonomies, true ) ) {
					$taxonomies[] = $taxonomy;
				}
			}
		} else {
			$taxonomies = $allowed_taxonomies;
		}
		$taxonomies = array_values( array_unique( $taxonomies ) );

		if ( empty( $taxonomies ) ) {
			wp_send_json( array() );
		}

		// Each select is array( 'taxonomy' => string, 'term' => comma separated term ids ).
		// Sanitized by hand rather than with map_deep(), which needs WordPress 4.4. Selects for a
		// taxonomy outside the allowed list are dropped, as is anything non-scalar.
		$selects = array();
		if ( isset( $_REQUEST['selects'] ) && is_array( $_REQUEST['selects'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			foreach ( wp_unslash( $_REQUEST['selects'] ) as $select ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( ! is_array( $select ) || ! isset( $select['taxonomy'] ) || ! is_string( $select['taxonomy'] ) || ! in_array( $select['taxonomy'], $allowed_taxonomies, true ) ) {
					continue;
				}
				$selects[] = array(
					'taxonomy' => $select['taxonomy'],
					'term'     => isset( $select['term'] ) && is_scalar( $select['term'] ) ? implode( ',', array_map( 'absint', explode( ',', (string) $select['term'] ) ) ) : '',
				);
			}
		}

		// Short circuit the database query entirely.
		// Return anything but null and that value is used instead of querying the database.
		// It has to be shaped exactly like the result of the query it replaces: an array of
		// objects with the properties term_count, term_id, term_name, term_slug and taxonomy.
		$related_terms = apply_filters( 'beautiful_filters_pre_related_terms', null, $post_type, $selects, $taxonomies );

		if ( null === $related_terms ) {
			$related_terms = $this->get_related_terms( $post_type, $selects, $taxonomies );
		}

		// The response is rebuilt from known fields only, for known taxonomies only, whatever the
		// query or a beautiful_filters_pre_related_terms callback handed back.
		$sorted = array();

		if ( $related_terms && is_array( $related_terms ) ) {
			foreach ( $related_terms as $term ) {
				$term = (object) $term;
				if ( ! isset( $term->taxonomy, $term->term_id, $term->term_name ) || ! in_array( $term->taxonomy, $taxonomies, true ) ) {
					continue;
				}
				$sorted[ $term->taxonomy ][] = array(
					'term_count' => isset( $term->term_count ) ? absint( $term->term_count ) : 0,
					'term_id'    => absint( $term->term_id ),
					// Sent HTML escaped, never decoded: the JS decodes it as inert text. esc_html()
					// doesn't double encode, so a stored name like "Rock &amp; Roll" goes out unchanged.
					'term_name'  => esc_html( apply_filters( 'list_cats', $term->term_name, $term->taxonomy ) ),
					'term_slug'  => isset( $term->term_slug ) ? sanitize_title( $term->term_slug ) : '',
					'taxonomy'   => $term->taxonomy,
				);
			}
		}

		// Sent as application/json (with admin-ajax' nosniff header) and with < > & ' " escaped,
		// so a browser navigating straight to the endpoint never parses the response as HTML.
		wp_send_json( $sorted, null, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
	}

	/**
	 * Queries the database for the terms that still return results for the current selection.
	 *
	 * Split out of update_filters_callback() so the response is shaped and sent in a single
	 * place whether the query ran or was short circuited.
	 *
	 * @since   2.6.0
	 * @param   string $post_type  The post type being filtered.
	 * @param   array  $selects    The currently selected taxonomies and terms.
	 * @param   array  $taxonomies The taxonomies of the post type being filtered.
	 * @return  array  An array of term objects with the properties term_count, term_id,
	 *                 term_name, term_slug and taxonomy. Empty if there's nothing to match.
	 */
	private function get_related_terms( $post_type, $selects, $taxonomies ) {

		global $wpdb;

		$all_other_terms_query = new WP_Term_Query(
			[
				'taxonomy' => $taxonomies,
				'fields'   => 'ids',
			]
		);
		$all_other_term_ids = array_map( 'absint', (array) $all_other_terms_query->terms );

		// Without any terms to match against, the final AND condition of the query below
		// would end up as an empty IN() list, which is a syntax error. Nothing could match
		// anyway, so hand back an empty result set instead of running a broken query.
		if ( empty( $all_other_term_ids ) ) {
			return array();
		}

		$all_other_terms = implode( ',', $all_other_term_ids );
		$sql_joins       = [];
		$sql_ands        = [];

		if ( $selects ) {
			foreach ( array_values( $selects ) as $i => $select ) {
				// Don't query if term is not set.
				if ( ! isset( $select['term'] ) || $select['term'] === 0 || $select['term'] === '0' || $select['term'] === '' ) {
					continue;
				}

				// Already checked against the allowed taxonomies by the caller, but this method
				// shouldn't rely on that.
				if ( ! isset( $select['taxonomy'] ) || ! is_string( $select['taxonomy'] ) || ! taxonomy_exists( $select['taxonomy'] ) ) {
					continue;
				}

				// Cast as array and run it through absint to prevent SQL injection.
				// This also allows us to easier handle multiple term selections in the future.
				$term_ids = array_filter( array_map( 'absint', explode( ',', $select['term'] ) ) );

				// Bail if it's now empty for some reason.
				// Shouldn't really happen but better safe than sorry.
				if ( empty( $term_ids ) ) {
					continue;
				}

				// A numbered table alias per join. Nothing from the request ends up in the SQL as an
				// identifier, and taxonomy names that aren't valid SQL identifiers (like "book-shelf")
				// no longer break the query.
				$alias = 'btf_tr' . absint( $i );

				// Add the taxonomy join.
				$sql_joins[] = "
					LEFT JOIN {$wpdb->prefix}term_relationships AS {$alias}
					ON {$wpdb->prefix}posts.ID = {$alias}.object_id
				";

				// Add the AND condition for the current taxonomy.
				$placeholders = implode( ',', array_fill( 0, count( $term_ids ), '%d' ) );
				$sql_ands[] = $wpdb->prepare(
					"AND ( {$alias}.term_taxonomy_id IN ( $placeholders ) )", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
					$term_ids
				);
			}
		}

		// Let's build the SQL query.
		// We use the prepare method here because WP requires us to do so.
		// But we have to do it a bit differently because it's a rather limited method that butchers the SQL, especially when trying to use it with an IN statement.
		// I've searched high and low for anyone that has found a way to properly use IN with prepared statements...
		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
		$sql = $wpdb->prepare(
			"
			SELECT COUNT( DISTINCT {$wpdb->prefix}posts.ID ) as term_count,
						terms.term_id as term_id,
						terms.name as term_name,
						terms.slug as term_slug,
						term_taxonomy.taxonomy as taxonomy
			FROM {$wpdb->prefix}posts
			INNER JOIN {$wpdb->prefix}term_relationships AS term_relationships
				ON {$wpdb->prefix}posts.ID = term_relationships.object_id
			INNER JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->prefix}terms AS terms USING( term_id )
			# Add dynamic JOIN conditions
			" . implode( ' ', $sql_joins ) . "
			WHERE {$wpdb->prefix}posts.post_type = %s AND {$wpdb->prefix}posts.post_status = 'publish'
			# Add dynamic AND conditions
			" . implode( ' ', $sql_ands ) . "
			# Add the final AND condition. We can't use a placeholder here because it's a list of ids and it gets fcked up.
			AND terms.term_id IN ( ". $all_other_terms ." )
			# end with grouping and ordering
			GROUP BY terms.term_id
			HAVING term_count > 0
			ORDER BY term_count DESC
			LIMIT 1000
			",
			$post_type
		);
		// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared

		// Filter the finished query.
		// Note that the string handed to you has already been through $wpdb->prepare(),
		// so anything you splice into it is your own responsibility, and any % characters
		// in the string you return will not be processed again.
		$sql = apply_filters( 'beautiful_filters_related_terms_sql', $sql, $post_type, $selects, $taxonomies );

		return $wpdb->get_results( $sql ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
	}



	/**
	* Runs on template_include filter. Check for $POST values coming from the filter and add them to the url
	* Also check for custom GET parameters and reattach them to the url to support combination with other functionalities
	*
	* @since    1.0.0
	*/
	public function catch_filter_values() {
		//Nope, this pageload was not due to our filter!
		if ( ! isset( $_POST['btf_do_filtering_nonce'] ) || ! is_string( $_POST['btf_do_filtering_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['btf_do_filtering_nonce'] ) ), 'Beutiful-taxonomy-filters-do-filter' ) ) {
			return;
		}

		//get current post type archive
		if ( isset( $_POST['post_type'] ) && is_string( $_POST['post_type'] ) && '' !== $_POST['post_type'] ) {
			$current_post_type = sanitize_key( wp_unslash( $_POST['post_type'] ) );
		} else {
			//If there was no post type from the form (for some reason), try to get it anyway!
			$current_post_type = self::get_current_posttype( false );
		}

		//post type validation. Only post types the filter is enabled on, like the filter module itself.
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
		if ( ! $current_post_type || ! post_type_exists( $current_post_type ) || ! is_array( $post_types ) || ! in_array( $current_post_type, $post_types, true ) ) {
			return;
		}

		$new_url = trailingslashit( get_post_type_archive_link( $current_post_type ) );

		//The url above already contains the post type archive slug. We need it here as well
		//so we don't add it to the url a second time for taxonomies nested underneath it.
		$post_type_slug = btf_get_post_type_archive_slug( $current_post_type );

		//Get the taxonomies of the current post type
		$current_taxonomies = btf_get_current_taxonomies( $current_post_type );
		if ( $current_taxonomies ) {
			foreach ( $current_taxonomies as $key => $value ) {

				//check for each taxonomy as a $_POST variable.
				//If it exists we want to append it along with the value (term) it has.
				//The option values are term ids.
				$term = ( isset( $_POST[ 'select-' . $key ] ) && is_scalar( $_POST[ 'select-' . $key ] ) ? absint( $_POST[ 'select-' . $key ] ) : 0 );
				if ( $term ) {
					$term_object = get_term( $term, $key );
					//Not a WP_Term check: that class doesn't exist before WordPress 4.4.
					if ( ! is_object( $term_object ) || is_wp_error( $term_object ) || empty( $term_object->slug ) ) {
						continue;
					}
					//Resolve the url segment for the taxonomy. Must be identical to the one used
					//for the rewrite rules or the url we redirect to won't resolve!
					$rewrite_slug = btf_get_taxonomy_rewrite_slug( $value, $post_type_slug );
					$new_url .= trailingslashit( $rewrite_slug . '/' . $term_object->slug );
				}
			}
		}

		//Perform actions before the redirect to the filtered page
		do_action( 'beautiful_actions_before_redirection', $current_post_type );

		//keep GET parameters
		$new_url = $this->append_get_parameters( $new_url );

		//sanitize URL
		$new_url = esc_url_raw( $new_url );

		$redirect_url = apply_filters( 'beautiful_filters_new_url', $new_url, $current_post_type );

		//The url we build ourselves always points at this site, so the filter above is the only
		//thing that can send a visitor somewhere else. Refuse to follow it off site.
		//We check the location ourselves rather than leaning on wp_safe_redirect()'s own fallback,
		//which is the admin url and would drop a logged out visitor on the login screen. Falling
		//back to the url we built keeps them on the archive they were filtering.
		//Hosts that should be allowed, language domains for example, belong in WordPress' own
		//allowed_redirect_hosts filter. Polylang already registers its domains there.
		if ( ! wp_validate_redirect( $redirect_url, false ) ) {
			$redirect_url = $new_url;
		}

		//perform a redirect to the new filtered url
		wp_safe_redirect( $redirect_url );
		exit;
	}


	/**
	* Attempts to automagically insert the filter on the correct archives by using the loop_start hook
	*
	* @since 1.1.1
	*/
	public function automagic_insertion( $query ) {

		$post_types = get_option( 'beautiful_taxonomy_filters_post_types' );

		//first make sure we're on a main query and an archive page for one of our selected posttypes
		if ( $query->is_main_query() && is_post_type_archive( $post_types ) && ! is_feed() ) {

			$automagic = get_option( 'beautiful_taxonomy_filters_automagic' );
			if ( ! $automagic ) {
				return;
			}

			if ( in_array( 'filter_info_module', $automagic ) && in_array( 'above', $automagic ) ) {
				self::beautiful_filters_info();
			}

			if ( in_array( 'filter_module', $automagic ) ) {
				self::beautiful_filters( false );
			}

			if ( in_array( 'filter_info_module', $automagic ) && in_array( 'below', $automagic ) ) {
				self::beautiful_filters_info();
			}
		}

	}


	/**
	 * Modifies the select elements belonging to BTF.
	 * Mostly just adding data parameters to use with our JS functions.
	 *
	 * @since   1.3
	 */
	public function modify_select_elements( $select, $parameters ) {

		/**
		 * If there's no class at all just return, not our stuff!
		 */
		if ( ! isset( $parameters['class'] ) || $parameters['class'] == '' ) {
			return $select;
		}

		/**
		 * So there's atleast one class.. if none is ours this is still not our stuff!
		 */
		$classes = explode( ' ', $parameters['class'] );
		if ( ! in_array( 'beautiful-taxonomy-filters-select', $classes ) ) {
			return $select;
		}

		/**
		 * Save our entire wp_dropdown_categories arguments array as a serialized value.
		 */
		$save_parameters = $parameters;
		if ( isset( $save_parameters['walker'] ) ) {
			unset( $save_parameters['walker'] );
		}
		$new_select = str_replace( '<select', '<select data-taxonomy="' . esc_attr( $parameters['taxonomy'] ) . '" data-options="' . esc_attr( wp_json_encode( $save_parameters ) ) . '" data-nonce="' . esc_attr( wp_create_nonce( 'update_btf_selects_security' ) ) . '"', $select );

		return $new_select;
	}


	/**
	* Public function to return the filters for the current post type archive.
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters( $post_type, $args = array() ) {
		// Whether to echo the module (default, for template tags/actions/widgets/automagic)
		// or capture and return it (used by the shortcode so it renders in place).
		$echo = ! isset( $args['echo'] ) || $args['echo'];

		//Fetch the plugins options
		//Apply filters on them to let users modify the options before they're being used!
		$post_types = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );

		//If there's no post types, bail early! Also guard against a filter returning a non-array,
		//which would fatal in the in_array() checks below on PHP 8.
		if ( empty( $post_types ) || ! is_array( $post_types ) ) {
			return;
		}

		//get current post type archive
		if ( $post_type ) {

			$current_post_type = $post_type;
			//Take the slug the post type uses for its archive url, which is the one we actually want!
			$current_post_type_rewrite = btf_get_post_type_archive_slug( $current_post_type );

		} elseif ( isset( $_POST['post_type'] ) && is_string( $_POST['post_type'] ) && '' !== $_POST['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$current_post_type = sanitize_key( wp_unslash( $_POST['post_type'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
			//Take the slug the post type uses for its archive url, which is the one we actually want!
			$current_post_type_rewrite = btf_get_post_type_archive_slug( $current_post_type );

		} else { //If there was no post type from the form (for some reason) and there is no post type supplied by the public function, try to get it anyway!

			$current_post_type         = self::get_current_posttype( false );
			$current_post_type_rewrite = self::get_current_posttype();

		}

		//not a post type we have the filter on or perhaps not even a registered post type, bail early!
		if ( ! post_type_exists( $current_post_type ) || ! in_array( $current_post_type, $post_types ) ) {
			return;
		}

		//do some taxonomy checking will ya
		$current_taxonomies = btf_get_current_taxonomies( $current_post_type );

		//On a post type that we want the filter on, and we have atleast one valid taxonomy
		if ( in_array( $current_post_type, $post_types ) && ! empty( $current_taxonomies ) ) {

			if ( ! $echo ) {
				ob_start();
			}

			self::enqueue_module_scripts();

			require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-display.php';

			if ( ! $echo ) {
				return ob_get_clean();
			}

		}

	}

	/**
	* Public function to return information about the currently active filters
	*
	* @since 1.0.0
	*/
	public static function beautiful_filters_info( $args = array() ) {
		// Whether to echo the module (default) or capture and return it (shortcode).
		$echo = ! isset( $args['echo'] ) || $args['echo'];

		global $wp_query;
		$current_taxonomies = ( isset( $wp_query->tax_query->queries ) ) ? $wp_query->tax_query->queries : false;
		if ( false == $current_taxonomies ) {
			return;
		}

		$post_types         = apply_filters( 'beautiful_filters_post_types', get_option( 'beautiful_taxonomy_filters_post_types' ) );
		$current_post_type  = self::get_current_posttype( false );

		//If there is no current post type, bail early! The is_array() check guards against a filter
		//returning a non-array, which would fatal in in_array() on PHP 8.
		if ( ! is_array( $post_types ) || ! post_type_exists( $current_post_type ) || ! in_array( $current_post_type, $post_types ) ) {
			return;
		}

		if ( ! $echo ) {
			ob_start();
		}

		require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/beautiful-taxonomy-filters-public-info-display.php';

		if ( ! $echo ) {
			return ob_get_clean();
		}

	}

}
