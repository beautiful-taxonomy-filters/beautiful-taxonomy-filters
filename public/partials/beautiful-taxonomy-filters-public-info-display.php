<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to provide the visitors with information about the currently active filters
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/public/partials
 */

 /*
 * Filters in this file
 * @ beautiful_filters_active_taxonomy
 * @ beautiful_filters_active_terms
 */
?>
<?php
$hide_heading = apply_filters( 'beautiful_filters_disable_heading', get_option('beautiful_taxonomy_filters_disable_heading') );
$hide_postcount = apply_filters( 'beautiful_filters_disable_postcount', get_option('beautiful_taxonomy_filters_disable_postcount') );
global $wp_query;
$current_taxonomies = $wp_query->tax_query->queries;
$activated_post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );
$current_post_type = Beautiful_Taxonomy_Filters_Public::get_current_posttype(false);

//If there is no current post type, bail early!
if(!$current_post_type || !in_array($current_post_type, $activated_post_types)){
	return;
}
?>
<div class="beautiful-taxonomy-filters-active-filter">
	<?php do_action( 'beautiful_actions_beginning_filterinfo', $current_post_type); //Allow custom markup before filter info ?>
	<?php if(!$hide_heading): ?>
		<h3 class="beautiful-taxonomy-filters-info-heading"><?php echo apply_filters( 'beautiful_filters_info_heading', __('Active filters', 'beautiful-taxonomy-filters') ); ?></h3>
	<?php endif; ?>
	<?php if(!$hide_postcount): ?>
		<p class="beautiful-taxonomy-filters-postcount"><?php echo apply_filters( 'beautiful_filters_info_postcount', sprintf( __( 'Result of filter: %d', 'beautiful-taxonomy-filters' ), $wp_query->found_posts ) ); ?></p>

	<?php endif; ?>
	<?php $posttypes_taxonomies = get_object_taxonomies($current_post_type, 'objects'); ?>
	<?php if($current_taxonomies): ?>
		<?php
		//Get the taxonomies of the current post type and the excluded taxonomies
		$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') );
		//Also make sure we don't try to output the builtin taxonomies since they cannot be supported
		if(is_array($excluded_taxonomies)){
			array_push($excluded_taxonomies, 'category', 'post_tag', 'post_format');
		}else{
			$excluded_taxonomies = array(
				'category',
				'post_tag',
				'post_format'
			);
		}
		//Polylang support
		if(function_exists('pll_current_language')){
			array_push($excluded_taxonomies, 'language', 'post_translations');
		}
		//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
		if($current_taxonomies && $excluded_taxonomies){
			foreach($current_taxonomies as $key => $value){
				if(in_array($value['taxonomy'], $excluded_taxonomies)){
					unset($current_taxonomies[$key]);
				}
			}
		}
		foreach($posttypes_taxonomies as $key => $value){
			if(in_array($key, $excluded_taxonomies)){
				unset($posttypes_taxonomies[$key]);
			}
		}
		?>
		<?php foreach($current_taxonomies as $taxonomy): ?>
			<?php
			if(array_key_exists($taxonomy['taxonomy'], $posttypes_taxonomies)){
				unset($posttypes_taxonomies[$taxonomy['taxonomy']]);
			}
			?>
			<div class="beautiful-taxonomy-filters-single-tax">
				<?php
				//get the taxonomy object
				$taxonomy_info = get_taxonomy($taxonomy['taxonomy']);
				//Get the terms objects
				$terms = get_terms($taxonomy['taxonomy']);
				//setup an empty array for the actual terms we want.
				$active_terms = array();
				if(!is_wp_error($terms)){
					foreach($terms as $term){
						//If the term slugs matches, put it in the array!
						if(in_array($term->slug, $taxonomy['terms'])){
							$active_terms[$term->term_id] = $term->name;
						}
					}
				}
				//Implode that sh**t!
				$imploded_terms = implode(', ', $active_terms);
				$label = $taxonomy_info->labels->name . ':';
				?>
				<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy['taxonomy']); ?></span>
				<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $imploded_terms, $taxonomy['taxonomy']); ?></span>
			</div>
		<?php endforeach; ?>
		<?php if(!empty($posttypes_taxonomies)): foreach($posttypes_taxonomies as $taxonomy): ?>
			<div class="beautiful-taxonomy-filters-single-tax">
				<?php
				$label = $taxonomy->labels->name . ':';
				$value = $taxonomy->labels->all_items;
				?>
				<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy->query_var); ?></span>
				<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $value, $taxonomy->query_var); ?></span>
			</div>
		<?php endforeach; endif; ?>

	<?php else: ?>

		<?php
		//Get the taxonomies of the current post type and the excluded taxonomies
		$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') );
		if(is_array($excluded_taxonomies)){
			array_push($excluded_taxonomies, 'category', 'post_tag', 'post_format');
		}else{
			$excluded_taxonomies = array(
				'category',
				'post_tag',
				'post_format'
			);
		}
		//Polylang support
		if(function_exists('pll_current_language')){
			array_push($excluded_taxonomies, 'language', 'post_translations');
		}
		//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
		if($posttypes_taxonomies && $excluded_taxonomies){
			foreach($posttypes_taxonomies as $key => $value){
				if(in_array($key, $excluded_taxonomies)){
					unset($posttypes_taxonomies[$key]);
				}
			}
		}
		?>
		<?php if($posttypes_taxonomies): ?>

			<?php foreach($posttypes_taxonomies as $taxonomy):?>
				<div class="beautiful-taxonomy-filters-single-tax">
					<?php
					$label = $taxonomy->labels->name . ':';
					$value = $taxonomy->labels->all_items;
					?>
					<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy->query_var); ?></span>
					<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $value, $taxonomy->query_var); ?></span>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>

	<?php endif; ?>
	<?php do_action( 'beautiful_actions_ending_filterinfo', $current_post_type); //Allow custom markup before filter info ?>
</div>
