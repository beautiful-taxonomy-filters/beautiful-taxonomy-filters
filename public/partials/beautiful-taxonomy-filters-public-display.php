<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/public/partials
 */

/*
 * Filters in this file
 * @ beautiful_filters_post_types
 * @ beautiful_filters_post_types
 * @ beautiful_filters_clear_all
 * @ beautiful_filters_dropdown_categories
 * @ beautiful_filters_taxonomy_label
 */
/*
 * Actions in this file
 * @ beautiful_actions_before_form
 * @ beautiful_actions_beginning_form
 * @ beautiful_actions_end_form
 * @ beautiful_actions_after_form
 */

//Fetch the available settings for the filter modules behaviour
$settings = apply_filters( 'beautiful_filters_settings', get_option( 'beautiful_taxonomy_filters_settings' ), $current_post_type );
$conditional_dropdowns = ( isset( $settings['conditional_dropdowns'] ) ? $settings['conditional_dropdowns'] : false );
$show_clear_all = apply_filters( 'beautiful_filters_clear_all', get_option( 'beautiful_taxonomy_filters_clear_all' ), $current_post_type );
$hide_empty = apply_filters( 'beautiful_filters_hide_empty', get_option( 'beautiful_taxonomy_filters_hide_empty' ), $current_post_type );
$show_count = apply_filters( 'beautiful_filters_show_count', get_option( 'beautiful_taxonomy_filters_show_count' ), $current_post_type );
$dropdown_behaviour = apply_filters( 'beautiful_filters_dropdown_behaviour', get_option( 'beautiful_taxonomy_filters_dropdown_behaviour' ), $current_post_type );
$disable_select2 = ( get_option( 'beautiful_taxonomy_filters_disable_select2' ) ? get_option( 'beautiful_taxonomy_filters_disable_select2' ) : false );
?>
<div class="beautiful-taxonomy-filters <?php if ( ! $disable_select2 ) { echo 'select2-active'; } ?>" id="beautiful-taxonomy-filters-<?php echo $current_post_type_rewrite; ?>">
	<?php do_action( 'beautiful_actions_before_form', $current_post_type ); //Allow custom markup before form ?>
	<form method="POST" class="clearfix" id="beautiful-taxonomy-filters-form">
		<input type="hidden" name="site-url" value="<?php echo get_bloginfo( 'url' ); ?>" />
		<input type="hidden" name="post_type_rewrite" value="<?php echo esc_attr( $current_post_type_rewrite ); ?>" />
		<input type="hidden" name="post_type" value="<?php echo esc_attr( $current_post_type ); ?>" />
		<?php wp_nonce_field( 'Beutiful-taxonomy-filters-do-filter', 'btf_do_filtering_nonce' ); ?>
		<?php do_action( 'beautiful_actions_beginning_form', $current_post_type ); //allow custom markup at beginning of form ?>
		<?php
		//Loop through the taxonomies and output their terms in a select dropdown
		$count = count( $current_taxonomies );
		$taxonomies_ordered = apply_filters( 'beautiful_filters_taxonomy_order', array_keys( $current_taxonomies ), $current_post_type );
		?>
		<div class="beautiful-taxonomy-filters-select-wrap clearfix">
			<?php do_action( 'beautiful_actions_beginning_form_inner', $current_post_type ); //allow custom markup at beginning of form ?>
			<?php foreach ( $taxonomies_ordered as $key ) : ?>
				<?php
				$taxonomy = $current_taxonomies[ $key ];
				$terms = get_terms( $key );
				?>
				<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
					<?php do_action( 'beautiful_actions_before_select', $key, $current_post_type ); //Allow custom markup before each select ?>
					<div class="beautiful-taxonomy-filters-tax filter-count-<?php echo $count; if ( $count > 5 ) { echo ' filter-count-many'; } ?>" id="beautiful-taxonomy-filters-tax-<?php echo $key; ?>">
						<label for="select-<?php echo $key; ?>" class="beautiful-taxonomy-filters-label"><?php echo apply_filters( 'beautiful_filters_taxonomy_label', $taxonomy->labels->name, $taxonomy->name ); ?></label>
						<?php
						/**
						* Output the dropdown with the terms of the taxonomy.
						* Uses walker found in: public/class-beautiful-taxonomy-filters-walker.php
						*/
						$dropdown_args = array(
							'show_option_all' => $taxonomy->labels->all_items,
							'taxonomy'      => $key,
							'name'          => 'select-' . $key, //BUG?? For some reason we can't use the actual taxonomy slugs. If we do wordpress automatically fetches the correct posts without us even changing the URL.
							'show_count'    => $show_count,
							'hide_empty'    => $hide_empty,
							'orderby'       => apply_filters( 'beautiful_filters_dropdown_orderby', 'name', $key ),
							'order' 		=> apply_filters( 'beautiful_filters_dropdown_order', 'ASC', $key ),
							'hierarchical'  => true,
							'echo'          => false,
							'class'			=> 'beautiful-taxonomy-filters-select',
							'walker'        => new Walker_Slug_Value_Category_Dropdown,
						);
						//Apply filter on the arguments to let users modify them first!
						$dropdown_args = apply_filters( 'beautiful_filters_dropdown_categories', $dropdown_args, $taxonomy->name );

						//But if they've selected placeholder we cant use the show_option_all (they still have to use select2 tho)
						if ( ! $disable_select2 && 'show_placeholder_option' == $dropdown_behaviour ) {
							$dropdown_args['show_option_all'] = ' ';
						}

						//create the dropdown
						$filterdropdown = wp_dropdown_categories( $dropdown_args );

						//If they didnt select placeholder just output the dropdown now (or if they've disabled select2)
						if ( $disable_select2 || ! $dropdown_behaviour || 'show_all_option' == $dropdown_behaviour ) {

							echo $filterdropdown;

						} else {

							//They selected placeholder so now we need to choose what to display and then alter the dropdown before output.
							$new_label = apply_filters( 'beautiful_filters_dropdown_placeholder', $taxonomy->labels->all_items, $taxonomy->name );
							$filterdropdown = str_replace( "value='0' selected='selected'", '', $filterdropdown );
							echo str_replace( '<select ', '<select data-placeholder="' . $new_label . '"', $filterdropdown );

						}
						?>
						<?php is_btf_filtered(); ?>
						<?php if ( $conditional_dropdowns ) : ?>
							<span class="beautiful-taxonomy-filters-loader"><?php echo apply_filters( 'beautiful_filters_loader', sprintf( '<img src="%s" alt="" />', admin_url( 'images/spinner.gif' ) ), $key, $current_post_type ); ?></span>
						<?php endif; ?>
					</div>
					<?php do_action( 'beautiful_actions_after_select', $key, $current_post_type ); //Allow custom markup before each select ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php do_action( 'beautiful_actions_ending_form_inner', $current_post_type ); //allow custom markup at end of inner form ?>
		</div>
		<?php do_action( 'beautiful_actions_before_submit_button', $current_post_type ); //allow custom markup before submit button ?>
		<button type="submit" class="beautiful-taxonomy-filters-button"><?php echo apply_filters( 'beautiful_filters_apply_button', __( 'Apply filter', 'beautiful-taxonomy-filters' ) ); ?></button>
		<?php do_action( 'beautiful_actions_after_submit_button', $current_post_type ); //allow custom markup before submit button ?>
		<?php if ( $show_clear_all && is_btf_filtered() ) : ?>
			<a href="<?php echo get_post_type_archive_link( $current_post_type ); ?>" class="beautiful-taxonomy-filters-clear-all" title="<?php _e( 'Click to clear all active filters', 'beautiful-taxonomy-filters' ); ?>"><?php echo apply_filters( 'beautiful_filters_clear_button', __( 'Clear all', 'beautiful-taxonomy-filters' ) ); ?></a>
		<?php endif; ?>
		<?php do_action( 'beautiful_actions_ending_form', $current_post_type ); //allow custom markup at beginning of form ?>
	</form>
	<?php do_action( 'beautiful_actions_after_form', $current_post_type ); //Allow custom markup after form ?>
</div>
