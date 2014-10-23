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
?>
<?php 
$show_clear_all = apply_filters( 'beautiful_filters_clear_all', get_option('beautiful_taxonomy_filters_clear_all') );
//Make sure we find the current post type! Put it as a hidden form input. This assures us that we'll know where to redirect inside the plugin at all times
$current_post_type = get_post_type();
if(!$current_post_type || $current_post_type == 'page'){
	global $template;
	$template_name = explode('-', basename( $template, '.php' ));
	if (in_array('archive', $template_name) && count($template_name) > 1) {
		$current_post_type = $template_name[1];
	}else{
		//didnt find the post type in the template, fall back to the wp_query!
		global $wp_query;
		if($wp_query->query['post_type'] != ''){
			$current_post_type = $wp_query->query['post_type'];	
		}
	}
}
?>
<div class="beautiful-taxonomy-filters" id="beautiful-taxonomy-filters-<?php echo $current_post_type; ?>">
	<?php do_action( 'beautiful_actions_before_form', $current_post_type); //Allow custom markup before form ?>
	<form method="POST" class="clearfix" id="beautiful-taxonomy-filters-form">
		<input type="hidden" name="site-url" value="<?php echo get_bloginfo('url'); ?>" />
		<input type="hidden" name="post_type" value="<?php echo $current_post_type; ?>" />
		<?php do_action( 'beautiful_actions_beginning_form', $current_post_type); //allow custom markup at beginning of form ?>
		<?php 
		//Loop through the taxonomies and output their terms in a select dropdown 
		$count = count($current_taxonomies);	
		?>
		<div class="beautiful-taxonomy-filters-select-wrap clearfix">
			<?php foreach($current_taxonomies as $key => $taxonomy): ?>
				<?php $terms = get_terms($key); ?>
				<?php if(!empty($terms) && !is_wp_error($terms)): ?>
					<div class="beautiful-taxonomy-filters-tax filter-count-<?php echo $count; ?> filter-count-<?php if($count > 5){ echo 'many'; } ?>">
						<label for="select-<?php echo $key; ?>" class="beautiful-taxonomy-filters-label"><?php echo apply_filters( 'beautiful_filters_taxonomy_label', $taxonomy->labels->name, $taxonomy->name); ?></label>
						<?php
						/**
						* Output the dropdown with the terms of the taxonomy. 
						* Uses walker found in: public/class-beautiful-taxonomy-filters-walker.php
						*/
						$dropdown_args = array(
							'show_option_all' => __('All ', 'beautiful-taxonomy-filters') . $taxonomy->labels->name,
							'taxonomy'      => $key,
							'name'          => 'select-'.$key, //BUG?? For some reason we can't use the actual taxonomy slugs. If we do wordpress automatically fetches the correct posts without us even changing the URL HOWEVER it all breaks when the term has a non standard latin character in its name (not even in the slug which is what we actually use) such as åäö
							'show_count'    => 0,
							'hide_empty'    => 0,
							'orderby'       => 'name',
							'hierarchical'  => true,
							'echo'          => 0,
							'class'			=> 'beautiful-taxonomy-filters-select',
							'walker'        => new Walker_Slug_Value_Category_Dropdown
						);
						//Apply filter on the arguments to let users modify them first!
						$dropdown_args = apply_filters( 'beautiful_filters_dropdown_categories', $dropdown_args, $taxonomy->name );
						echo $filterdropdown = wp_dropdown_categories( $dropdown_args );
						?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<button type="submit" class="beautiful-taxonomy-filters-button"><?php _e('Apply filter', 'beautiful-taxonomy-filters'); ?></button>
		<?php if($show_clear_all): ?>
			<a href="<?php echo get_site_url() . '/' . $current_post_type; ?>" class="beautiful-taxonomy-filters-clear-all" title="<?php _e('Click to clear all active filters', 'beautiful-taxonomy-filters'); ?>"><?php _e('Clear all', 'beautiful-taxonomy-filters'); ?></a>
		<?php endif; ?>
		<?php do_action( 'beautiful_actions_ending_form', $current_post_type); //allow custom markup at beginning of form ?>
	</form>
	<?php do_action( 'beautiful_actions_after_form', $current_post_type); //Allow custom markup after form ?>
</div>