<?php

/**
 * The setting for the behaviour of the select2 dropdowns
 *
 * This file is used to setup a settings field
 *
 * @link       http://tigerton.se
 * @since      1.1.1
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */
?>
	<?php $option = get_option('beautiful_taxonomy_filters_dropdown_behaviour'); ?>
	<p><label for="all-radio">
		<input type="radio" value="show_all_option" id="all-radio" name="beautiful_taxonomy_filters_dropdown_behaviour" <?php if(!$option || $option == 'show_all_option'){ echo 'checked'; } ?> /> <?php _e('All option', 'beautiful-taxonomy-filters'); ?>
	</label></p>
	<p><label for="placeholder-radio">
		<input type="radio" value="show_placeholder_option" id="placeholder-radio" name="beautiful_taxonomy_filters_dropdown_behaviour" <?php if($option == 'show_placeholder_option'){ echo 'checked'; } ?> /> <?php _e('Placeholder', 'beautiful-taxonomy-filters'); ?>
	</label></p>
