<?php
/**
 * The setting to show a clear all link
 *
 * This file is used to setup a settings field
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */

$options = get_option('beautiful_taxonomy_filters_settings');

//Get the available taxonomies
$taxonomies = get_taxonomies(
	array(
		'public' => true,
		'_builtin' => false,
	),
	'objects'
);
$saved_taxonomies = get_option( 'beautiful_taxonomy_filters_taxonomies' );
?>
<input type="checkbox" name="beautiful_taxonomy_filters_settings[conditional_dropdowns]" value="1" <?php echo ( isset( $options['conditional_dropdowns'] ) && $options['conditional_dropdowns'] == 1 ? 'checked="checked"' : '' ); ?> />
<small><strong><?php _e('This is a beta feature. If you find issues with it please check the forum and post a new topic if needed.', 'beautiful-taxonomy-filters'); ?></strong></small>
<!-- TODO
<div class="btf-conditional-dropdowns-exclude">
	<?php if ( is_array( $saved_taxonomies ) ) : ?>
		<p>
			<?php _e( 'Exclude these taxonomies from being affected:', 'beautiful-taxonomy-filters' ); ?>
		</p>
		<?php if ( ! empty( $taxonomies ) ) : ?>
			<?php foreach ( $taxonomies as $taxonomy ) : ?>
				<?php
				if ( is_array( $saved_taxonomies ) && in_array( $taxonomy->name, $saved_taxonomies ) ) {
					// Not in our active taxonomies.
					continue;
				}
				?>
				<p>
					<label for="<?php echo $taxonomy->name; ?>-checkbox">
						<input type="checkbox" value="<?php echo $taxonomy->name; ?>" id="<?php echo $taxonomy->name; ?>-checkbox" name="beautiful_taxonomy_filters_excluded_taxonomies[]" <?php if ( is_array( $saved_taxonomies ) && in_array( $taxonomy->name, $saved_taxonomies ) ){ echo 'checked'; } ?> /> <?php echo $taxonomy->labels->name; ?>
					</label>
					<small>
						<?php
						if ( $taxonomy->object_type && ! empty( $taxonomy->object_type ) ) {
							echo implode( ', ', $taxonomy->object_type );
						}
						?>
					</small>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>

</div>
-->
