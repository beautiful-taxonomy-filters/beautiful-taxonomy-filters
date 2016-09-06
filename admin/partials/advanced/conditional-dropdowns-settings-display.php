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
?>
<input type="checkbox" name="beautiful_taxonomy_filters_settings[conditional_dropdowns]" value="1" <?php echo ( isset( $options['conditional_dropdowns'] ) && $options['conditional_dropdowns'] == 1 ? 'checked="checked"' : '' ); ?> />
<small><strong><?php _e('This is a beta feature. If you find issues with it please check the forum and post a new topic if needed.', 'beautiful-taxonomy-filters'); ?></strong></small>