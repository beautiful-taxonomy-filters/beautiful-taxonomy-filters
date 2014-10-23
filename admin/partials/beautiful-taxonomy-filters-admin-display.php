<?php

/**
 * The main settings content for our beautiful plugin.. 
 *
 * This file is used to setup the main settings area
 *
 * @link       http://tigerton.se
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/admin/partials
 */
?>
<?php
//flush rewrite rules when we load this page!
flush_rewrite_rules();
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e('Beautiful Taxonomy Filters', 'beautiful-taxonomy-filters'); ?></h2>
    <form method="post" action="options.php">
		<?php settings_fields('taxonomy-filters'); ?>
		<?php do_settings_sections('taxonomy-filters'); ?>
		<?php submit_button('Save Changes'); ?>
    </form>
    <h3 id="beautiful-taxonomy-filters-howto"><?php _e('How to use Beautiful Taxonomy Filters', 'beautiful-taxonomy-filters'); ?></h3>
    <ol>
		<li>
			<p><?php _e('Select which post types you want to use the filtering on.</br> <b>Note:</b> if the post type aren\'t connected to one or more taxonomies you wont see any results.', 'beautiful-taxonomy-filters'); ?></p>
		</li>
		<li>
			<p><?php _e('Exclude any taxonomies you don\'t want the users to be able to filter on.</br> <b>Note:</b> Only taxonomies that are connected to post types you have selected above will be affected', 'beautiful-taxonomy-filters'); ?></p>
		</li>
		<li>
			<p><?php _e('Save your changes.', 'beautiful-taxonomy-filters'); ?></p>
			<p><?php _e('<i>success! Your previously ugly taxonomy filtering is now oh so beautiful!</i>', 'beautiful-taxonomy-filters'); ?></p>
		</li>
		<li>
			<p><?php _e('To use the provided select dropdown filters. Copy:', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php show_beautiful_filters(); ?&gt;</code></pre> <?php _e('and paste it in your', 'beautiful-taxonomy-filters'); ?> <a href="<?php echo get_admin_url() . 'theme-editor.php?file=archive.php'; ?>" target="_blank">archive.php</a> <?php _e('file in your template. It should be placed somewhere above', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php if( have_posts() ) : ?&gt;</code></pre></p>
			<p><?php _e('<b>Note:</b> The layout of archive.php may differ drastically between different themes. We can\'t help you with the positioning of the function in your theme so please either just try some locations (you can just remove the function again if something breaks) or ask in your themes support forum (if available). Best of luck!', 'beautiful-taxonomy-filters'); ?></p>
			<p><?php _e('<b>Note 2:</b> The function will only show a filter if the post type archive is for a post type you\'ve selected in the settings above. So don\'t worry about filters in the wrong place.', 'beautiful-taxonomy-filters'); ?></p>
		</li>
		<li>
			<p><?php _e('To show the active filter info. Copy:', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php show_beautiful_filters_info(); ?&gt;</code></pre> <?php _e('to wherever you want the filter info to appear.', 'beautiful-taxonomy-filters'); ?></p>
		</li>
	</ol>
</div>
