<?php

/**
 * The setting for automagically inserting the modules in the archives
 *
 * This file is used to setup a settings field
 *
 *
 * @since      1.1.1
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */
?>
<?php $automagic = get_option('beautiful_taxonomy_filters_automagic'); ?>

<p><label for="filter-module-checkbox">
	<input type="checkbox" value="filter_module" id="filter-module-checkbox" name="beautiful_taxonomy_filters_automagic[]" <?php if(is_array($automagic) && in_array('filter_module', $automagic)){ echo esc_attr( 'checked' ); } ?> /> <?php esc_html_e('Filter module', 'beautiful-taxonomy-filters'); ?>
</label></p>

<p><label for="filter-info-module-checkbox">
	<input type="checkbox" value="filter_info_module" id="filter-info-module-checkbox" name="beautiful_taxonomy_filters_automagic[]" <?php if(is_array($automagic) && in_array('filter_info_module', $automagic)){ echo esc_attr( 'checked' ); } ?> /> <?php esc_html_e('Filter info module', 'beautiful-taxonomy-filters'); ?>
</label></p>

<p><small><?php esc_html_e('Location of the filter info module', 'beautiful-taxonomy-filters'); ?></small></p>
<p><label for="filter-info-module-placement-select">
	<select name="beautiful_taxonomy_filters_automagic[]" id="filter-info-module-placement-select">
		<option value="above" <?php if( (is_array($automagic) && in_array('above', $automagic)) || (is_array($automagic) && !in_array('below', $automagic)) ){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Above the filter module', 'beautiful-taxonomy-filters'); ?></option>
		<option value="below" <?php if(is_array($automagic) && in_array('below', $automagic)){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Below the filter module', 'beautiful-taxonomy-filters'); ?></option>
	</select>
</label></p>
