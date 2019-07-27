<?php

/**
 * The setting to disable the heading for the active filters
 *
 * This file is used to setup a settings field
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */


$postcount = (get_option('beautiful_taxonomy_filters_disable_postcount') ? get_option('beautiful_taxonomy_filters_disable_postcount') : false); 
?>
<input type="checkbox" name="beautiful_taxonomy_filters_disable_postcount" value="1" <?php if($postcount){ echo 'checked="checked"'; } ?> />

