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
?>

<?php
//Get the available taxonomies
$taxonomies = get_taxonomies(
	array(
		'public' => true,
		'_builtin' => false
	),
	'objects'
);
//Get saved post types
$saved_taxonomies = get_option('beautiful_taxonomy_filters_taxonomies');
?>
<?php if(!empty($taxonomies)): foreach($taxonomies as $taxonomy): ?>
	<p><label for="<?php echo $taxonomy->name; ?>-checkbox">
		<input type="checkbox" value="<?php echo $taxonomy->name; ?>" id="<?php echo $taxonomy->name; ?>-checkbox" name="beautiful_taxonomy_filters_taxonomies[]" <?php if(is_array($saved_taxonomies) && in_array($taxonomy->name, $saved_taxonomies)){ echo 'checked'; } ?> /> <?php echo $taxonomy->labels->name; ?>
	</label></p>
<?php endforeach; endif; ?>