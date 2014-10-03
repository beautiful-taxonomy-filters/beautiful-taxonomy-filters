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


$clear_all = (get_option('beautiful_taxonomy_filters_clear_all') ? get_option('beautiful_taxonomy_filters_clear_all') : false); 
?>
<input type="checkbox" name="beautiful_taxonomy_filters_clear_all" value="1" <?php if($clear_all){ echo 'checked="checked"'; } ?> />

