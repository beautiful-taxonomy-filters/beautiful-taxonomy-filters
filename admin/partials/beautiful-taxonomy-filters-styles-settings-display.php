<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */


$selected_style = get_option('beautiful_taxonomy_filters_styles'); 
$styles = array(
	'basic' => 'No Styling',
	'light-material' => 'Light Material Design',
	'dark-material' => 'Dark Material Design'
);
echo '<select name="beautiful_taxonomy_filters_styles">';
foreach($styles as $key => $value){
	echo '<option value="' . $key . '"';
	if($key == $selected_style){
		echo ' selected="selected"';
	}
	echo '>' . $value . '</option>';
}
echo '</select>';
?>

