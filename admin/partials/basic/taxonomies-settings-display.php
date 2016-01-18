<?php

/**
 * The setting to exclude taxonomies from the filters form
 *
 * This file is used to setup a settings field
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
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
	<p>
		<label for="<?php echo $taxonomy->name; ?>-checkbox">
			<input type="checkbox" value="<?php echo $taxonomy->name; ?>" id="<?php echo $taxonomy->name; ?>-checkbox" name="beautiful_taxonomy_filters_taxonomies[]" <?php if(is_array($saved_taxonomies) && in_array($taxonomy->name, $saved_taxonomies)){ echo 'checked'; } ?> /> <?php echo $taxonomy->labels->name; ?>
		</label>
		<small>
			<?php
			if( $taxonomy->object_type && !empty( $taxonomy->object_type ) ){
				echo implode(', ', $taxonomy->object_type);
			}
			?>
		</small>
	</p>
<?php endforeach; endif; ?>