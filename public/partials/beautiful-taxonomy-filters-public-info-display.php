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
<?php $hide_heading = apply_filters( 'beautiful_filters_disable_heading', get_option('beautiful_taxonomy_filters_disable_heading') ); ?>
<div class="beautiful-taxonomy-filters-active-filter">
	<?php if(!$hide_heading): ?>
		<h3 class="beautiful-taxonomy-filters-info-heading"><?php _e('Active filters', 'beautiful-taxonomy-filters'); ?></h3>
	<?php endif; ?>
	<?php foreach($taxonomies as $taxonomy): ?>
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
</div>
