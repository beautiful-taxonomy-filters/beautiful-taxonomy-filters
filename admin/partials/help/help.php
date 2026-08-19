<div class="clearfix">
	<div class="beautiful-taxonomy-filters-right-col">
		<h3><?php esc_html_e('How to use Beautiful Taxonomy Filters', 'beautiful-taxonomy-filters'); ?></h3>
		<ol>
			<li>
				<p><?php esc_html_e('Select the post types you want to use the filtering on.</br> <b>Note:</b> if the post type aren\'t connected to one or more taxonomies you wont see any results.', 'beautiful-taxonomy-filters'); ?></p>
			</li>
			<li>
				<p><?php esc_html_e('Exclude any taxonomies you don\'t want the users to be able to filter on.</br> <b>Note:</b> Only affects taxonomies that are connected to post types you have selected', 'beautiful-taxonomy-filters'); ?></p>
			</li>
			<li>
				<p><?php esc_html_e('Save Changes.', 'beautiful-taxonomy-filters'); ?></p>
				<p><i><?php esc_html_e('success! Your previously ugly taxonomy filtering is now oh so beautiful!', 'beautiful-taxonomy-filters'); ?></i></p>
			</li>
			<li>
				<p><?php esc_html_e('To use the dropdown filter module you can either use the widgets, the automagic feature found in the Basic tab or copy:', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php do_action('show_beautiful_filters'); ?&gt;</code></pre> <?php esc_html_e('and paste it in your', 'beautiful-taxonomy-filters'); ?> <a href="<?php echo esc_url( get_admin_url() . 'theme-editor.php?file=archive.php' ); ?>" target="_blank">archive.php</a> <?php esc_html_e('file in your template. It should be placed somewhere above', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php if( have_posts() ) : ?&gt;</code></pre></p>
				<p><?php esc_html_e('<b>Note:</b> The layout of archive.php may differ drastically between different themes. We can\'t help you with the placement of the function in your theme so just try some locations (you can just remove the function again if something breaks) or ask in your themes support forum. Best of luck!', 'beautiful-taxonomy-filters'); ?></p>
			</li>
			<li>
				<p><?php esc_html_e('To show the active filter info either use the widgets, automagic feature or copy:', 'beautiful-taxonomy-filters'); ?> <pre><code>&lt;?php do_action('show_beautiful_filters_info'); ?&gt;</code></pre> <?php esc_html_e('to wherever you want the filter info to appear.', 'beautiful-taxonomy-filters'); ?></p>
			</li>
		</ol>

		<h3><?php esc_html_e('I need more help!', 'beautiful-taxonomy-filters'); ?></h3>
		<p><?php
		// Translators: %s is a placeholder for an URL.
		echo sprintf( __('I try to be as helpful as I can. You can find several topics in the WordPress forum or ask for help to a new issue. It\'s also possible to start an issue on <a href="%s">github.</a>', 'beautiful-taxonomy-filters'), 'https://github.com/jonathan-dejong/beautiful-taxonomy-filters'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?></p>
		<p><strong><?php esc_html_e('Please take a look in the FAQ and search the forum before starting a new topic.', 'beautiful-taxonomy-filters'); ?></strong></p>
		<p><a href="https://wordpress.org/support/plugin/beautiful-taxonomy-filters" class="button-secondary" target="_blank"><?php esc_html_e('Check the forum', 'beautiful-taxonomy-filters'); ?></a> <a href="https://wordpress.org/plugins/beautiful-taxonomy-filters/faq/" class="button-secondary" target="_blank"><?php esc_html_e('Read the FAQ', 'beautiful-taxonomy-filters'); ?></a></p>

		<h3><?php esc_html_e('I want to change the plugins behaviour', 'beautiful-taxonomy-filters'); ?></h3>
		<p><?php esc_html_e('There is a ton of filters and actions you can use to change BTFs behavior, add functionality and tweak stuff. If you find that you are in need of a hook that isn\'t there contact me and I will try to help you out!', 'beautiful-taxonomy-filters'); ?></p>
		<p><a href="https://wordpress.org/plugins/beautiful-taxonomy-filters/other_notes/" class="button-secondary" target="_blank"><?php esc_html_e('Learn about hooks and API', 'beautiful-taxonomy-filters'); ?></a></p>

	</div>
</div>
