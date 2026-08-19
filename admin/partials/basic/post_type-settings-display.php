<?php

/**
 * The setting for which post types the filter should be applicable to
 *
 * This file is used to setup a settings field
 *
 *
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
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
<?php if(!empty($post_types)): ?>
	<?php foreach($post_types as $post_type): ?>
		<p>
			<label for="<?php echo esc_attr( $post_type->name ); ?>-checkbox">
				<input type="checkbox" value="<?php echo esc_attr( $post_type->name ); ?>" id="<?php echo esc_attr( $post_type->name ); ?>-checkbox" name="beautiful_taxonomy_filters_post_types[]" <?php if(is_array($saved_post_types) && in_array($post_type->name, $saved_post_types)){ echo esc_attr( 'checked' ); } ?> /> <?php echo esc_html( $post_type->labels->name ); ?>
			</label>
			<?php $archive_link = get_post_type_archive_link($post_type->name); ?>
			<small><a href="<?php echo esc_url( $archive_link ); ?>" target="_blank" title="<?php esc_html_e('Open in new tab', 'beautiful-taxonomy-filters'); ?>"><?php echo esc_url( $archive_link ); ?></a></small>
		</p>
	<?php endforeach; ?>
<?php else: ?>
	<p><?php esc_html_e( 'No custom post types found', 'beautiful-taxonomy-filters' ); ?></p>
<?php endif; ?>
