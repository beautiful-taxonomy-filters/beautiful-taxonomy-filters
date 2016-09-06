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
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('Beautiful Taxonomy Filters', 'beautiful-taxonomy-filters'); ?></h2>
	<p><a href="<?php echo admin_url() ?>/options-general.php?page=taxonomy-filters&tab=help" class"margin"><?php _e('How to use Beautiful Taxonomy Filters', 'beautiful-taxonomy-filters'); ?></a></p>
	<h2 class="nav-tab-wrapper">
		<?php
	    $tabs = array(
		    'basic' => __('Basic options', 'beautiful-taxonomy-filters'),
		    'advanced' => __('Advanced options', 'beautiful-taxonomy-filters'),
		    'help' => __('Help', 'beautiful-taxonomy-filters'),
		    'about' => __('About', 'beautiful-taxonomy-filters')
	    );
	    //set current tab
	    $tab = ( isset($_GET['tab']) ? $_GET['tab'] : 'basic' );
	    ?>
	    <?php foreach( $tabs as $key => $value ): ?>
	    	<a class="nav-tab <?php if( $tab == $key ){ echo 'nav-tab-active'; } ?>" href="<?php echo admin_url() ?>/options-general.php?page=taxonomy-filters&tab=<?php echo $key; ?>"><?php echo $value; ?></a>
	    <?php endforeach; ?>
	</h2>

	<div class="beautiful-taxonomy-filters-tabs">
		<?php if( $tab == 'basic' ): ?>

			<?php flush_rewrite_rules(); ?>
		    <form method="post" action="options.php">
				<?php settings_fields('taxonomy-filters'); ?>
				<?php do_settings_sections('taxonomy-filters'); ?>
				<?php submit_button('Save Changes'); ?>
		    </form>

		<?php elseif( $tab == 'advanced' ): ?>

			<?php flush_rewrite_rules(); ?>
			<form method="post" action="options.php">
				<?php settings_fields('advanced-taxonomy-filters'); ?>
				<?php do_settings_sections('advanced-taxonomy-filters'); ?>
				<?php submit_button('Save Changes'); ?>
		    </form>

		<?php elseif( $tab == 'help' ): ?>

			<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/help/help.php'; ?>

		<?php else: ?>

			<?php include plugin_dir_path( dirname( __FILE__ ) ) . 'partials/about/about.php'; ?>

	   <?php endif; ?>
	</div>
</div>
