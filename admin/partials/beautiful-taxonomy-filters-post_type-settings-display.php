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
//Get the available public post types
$post_types = get_post_types(
	array(
		'public' => true,
		'_builtin' => false
	),
	'objects'
);
//Get saved post types
$saved_post_types = get_option('beautiful_taxonomy_filters_post_types');
?>
<?php if(!empty($post_types)): foreach($post_types as $post_type): ?>
	<p><label for="<?php echo $post_type->name; ?>-checkbox">
		<input type="checkbox" value="<?php echo $post_type->name; ?>" id="<?php echo $post_type->name; ?>-checkbox" name="beautiful_taxonomy_filters_post_types[]" <?php if(is_array($saved_post_types) && in_array($post_type->name, $saved_post_types)){ echo 'checked'; } ?> /> <?php echo $post_type->labels->name; ?>
	</label></p>
<?php endforeach; endif; ?>