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
//Fetch the plugins options
//Apply filters on them to let users modify the options before they're being used!
$post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') ); 
$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') ); 
$show_clear_all = apply_filters( 'beautiful_filters_clear_all', get_option('beautiful_taxonomy_filters_clear_all') ); 

//If there's no post types, just return nothing
if(!$post_types) 
	return;

//Fetch current post type info
$current_post_type = get_post_type();

//Get the taxonomies of the current post type
$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');
//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
if($current_taxonomies && $excluded_taxonomies){
	foreach($current_taxonomies as $key => $value){
		if(in_array($key, $excluded_taxonomies)){
			unset($current_taxonomies[$key]);
		}
	}
}
?>
<?php if(in_array($current_post_type, $post_types) && !empty($current_taxonomies)): ?>
	<div class="beautiful-taxonomy-filters" id="beautiful-taxonomy-filters-<?php echo $current_post_type; ?>">
		<?php do_action( 'beautiful_actions_before_form', $current_post_type); //Allow custom markup before form ?>
		<form method="POST" class="clearfix" id="beautiful-taxonomy-filters-form">
			<input type="hidden" name="site-url" value="<?php echo get_bloginfo('url'); ?>" />
			<?php do_action( 'beautiful_actions_beginning_form', $current_post_type); //allow custom markup at beginning of form ?>
			<?php 
			//Loop through the taxonomies and output their terms in a select dropdown 
			$count = count($current_taxonomies);	
			?>
			<div class="beautiful-taxonomy-filters-select-wrap clearfix">
				<?php foreach($current_taxonomies as $key => $taxonomy): ?>
					<?php $terms = get_terms($key); ?>
					<?php if(!empty($terms) && !is_wp_error($terms)): ?>
						<div class="beautiful-taxonomy-filters-tax filter-count-<?php if($count < 5){ echo $count; }else{ echo 'many'; } ?>">
							<label for="select-<?php echo $key; ?>" class="beautiful-taxonomy-filters-label"><?php echo apply_filters( 'beautiful_filters_taxonomy_label', $taxonomy->labels->name ); ?></label>
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
							$dropdown_args = apply_filters( 'beautiful_filters_dropdown_categories', $dropdown_args );
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
<?php endif; ?>
