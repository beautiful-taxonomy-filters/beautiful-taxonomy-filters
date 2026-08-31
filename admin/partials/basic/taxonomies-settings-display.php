<?php

/**
 * The setting to exclude taxonomies from the filters form
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
//Get the available taxonomies
$taxonomies = get_taxonomies(
	array(
		'public' => true,
		'_builtin' => false,
	),
	'objects'
);
//Get saved taxonomies
$saved_taxonomies = get_option( 'beautiful_taxonomy_filters_taxonomies' );
?>

<?php if ( ! empty( $taxonomies ) ) : ?>
	<?php foreach ( $taxonomies as $taxonomy ) : ?>
		<p>
			<label for="<?php echo esc_attr( $taxonomy->name ); ?>-checkbox">
				<input type="checkbox" value="<?php echo esc_attr( $taxonomy->name ); ?>" id="<?php echo esc_attr( $taxonomy->name ); ?>-checkbox" name="beautiful_taxonomy_filters_taxonomies[]" <?php if ( is_array( $saved_taxonomies ) && in_array( $taxonomy->name, $saved_taxonomies ) ){ echo esc_attr( 'checked' ); } ?> /> <?php echo esc_html( $taxonomy->labels->name ); ?>
			</label>
			<small>
				<?php
				if ( $taxonomy->object_type && ! empty( $taxonomy->object_type ) ) {
					echo esc_html( implode( ', ', $taxonomy->object_type ) );
				}
				?>
			</small>
		</p>
	<?php endforeach; ?>
<?php else: ?>
	<p><?php esc_html_e( 'No custom taxonomies found', 'beautiful-taxonomy-filters' ); ?></p>
<?php endif; ?>
