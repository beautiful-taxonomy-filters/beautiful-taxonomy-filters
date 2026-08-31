<?php

/**
 * The setting to select a CSS stylesheet (or not) for the filters form
 *
 * This file is used to setup a settings field
 *
 *
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */


$selected_style = get_option('beautiful_taxonomy_filters_styles');
$styles = array(
	'basic' => __('No Styling', 'beautiful-taxonomy-filters'),
	'simple' => __('Simple', 'beautiful-taxonomy-filters'),
	'light-material' => __('Light Material Design', 'beautiful-taxonomy-filters'),
	'dark-material' => __('Dark Material Design', 'beautiful-taxonomy-filters')
);
?>
<select name="beautiful_taxonomy_filters_styles">
<?php
foreach($styles as $key => $value){
	printf(
		'<option value="%1$s" %2$s>%3$s</option>',
		esc_attr( $key ),
		selected( $key, $selected_style, false ),
		esc_html( $value )
	);
}
?>
</select>
