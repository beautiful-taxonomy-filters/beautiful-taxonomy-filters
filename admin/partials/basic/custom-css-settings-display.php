<?php

/**
 * The setting to put custom css in the wp_head hook
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
<textarea name="beautiful_taxonomy_filters_custom_css" rows="8" cols="50" placeholder="<?php _e('Example:', 'beautiful-taxonomy-filters'); ?>

.beautiful-taxonomy-filter-button{
	background:red;
}"><?php echo get_option('beautiful_taxonomy_filters_custom_css'); ?></textarea>