<?php
/**
 * Our main filter widget
 *
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/widget
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */
class Beautiful_Taxonomy_Filters_Widget extends WP_Widget {


 	/**
	 * Constructor for the widget
	 *
	 * @since    1.1.0
	 */
    public function __construct() {
        parent::__construct(
            'beautiful-taxonomy-filters-widget', // Base ID
            'Beautiful Filter', // Name
            array(
                'description' => __( 'Add a filter module to the sidebar', 'beautiful-taxonomy-filters' )
            ) // Args
        );
    }

    /**
	 * Admin form in the widget area
	 *
	 * @since    1.0.0
	 */
    public function form( $instance ) {

    	$title = ( !empty($instance) ? strip_tags($instance['title']) : '' );
    	$clear_all = ( !empty($instance) ? strip_tags($instance['clear_all']) : false );
    	$hide_empty = ( !empty($instance) ? strip_tags($instance['hide_empty']) : false );
    	$show_count = ( !empty($instance) ? strip_tags($instance['show_count']) : false );
    	$show_description = ( !empty($instance) ? strip_tags($instance['show_description']) : false );
    	$post_type = ( !empty($instance) ? strip_tags($instance['post_type']) : false );
    	$dropdown_behaviour = ( !empty($instance) ? strip_tags($instance['dropdown_behaviour']) : false );
    	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
    	<p>
            <label for="<?php echo $this->get_field_id('clear_all'); ?>"><?php _e('Enable a "clear all" link: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('clear_all'); ?>" name="<?php echo $this->get_field_name('clear_all'); ?>">
            	<option value="inherit" <?php if($clear_all == 'inherit' || !$clear_all){ echo 'selected'; } ?>><?php _e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($clear_all == 'enable'){ echo 'selected'; } ?>><?php _e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($clear_all == 'disable'){ echo 'selected'; } ?>><?php _e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e('Hide empty terms: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>">
            	<option value="inherit" <?php if($hide_empty == 'inherit' || !$hide_empty){ echo 'selected'; } ?>><?php _e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($hide_empty == 'enable'){ echo 'selected'; } ?>><?php _e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($hide_empty == 'disable'){ echo 'selected'; } ?>><?php _e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show post count: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name('show_count'); ?>">
            	<option value="inherit" <?php if($show_count == 'inherit' || !$show_count){ echo 'selected'; } ?>><?php _e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($show_count == 'enable'){ echo 'selected'; } ?>><?php _e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($show_count == 'disable'){ echo 'selected'; } ?>><?php _e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_description'); ?>"><?php _e('Show term description: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('show_description'); ?>" name="<?php echo $this->get_field_name('show_description'); ?>">
            	<option value="inherit" <?php if($show_description == 'inherit' || !$show_description){ echo 'selected'; } ?>><?php _e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($show_description == 'enable'){ echo 'selected'; } ?>><?php _e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($show_description == 'disable'){ echo 'selected'; } ?>><?php _e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('dropdown_behaviour'); ?>"><?php _e('Dropdown deselect/default behaviour:', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('dropdown_behaviour'); ?>" name="<?php echo $this->get_field_name('dropdown_behaviour'); ?>">
            	<option value="inherit" <?php if($dropdown_behaviour == 'inherit' || !$dropdown_behaviour){ echo 'selected'; } ?>><?php _e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="show_all_option" <?php if($dropdown_behaviour == 'show_all_option'){ echo 'selected'; } ?>><?php _e('All option', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="show_placeholder_option" <?php if($dropdown_behaviour == 'show_placeholder_option'){ echo 'selected'; } ?>><?php _e('Placeholder', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Specific posttype: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
            	<option value="automatic" <?php if($show_count == 'automatic' || !$post_type){ echo 'selected'; } ?>><?php _e('Automatic (default)', 'beautiful-taxonomy-filters'); ?></option>
            	<?php
	            $post_types = get_post_types(
					array(
						'public' => true,
						'_builtin' => false
					),
					'objects'
				);
				$activated_post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );
	            ?>
	            <?php if($post_types): foreach($post_types as $single_post_type): ?>
	            	<?php if(in_array($single_post_type->name, $activated_post_types)): ?>
		            	<option value="<?php echo $single_post_type->name; ?>" <?php if($post_type == $single_post_type->name){ echo 'selected'; } ?>><?php echo $single_post_type->labels->name; ?></option>
		            <?php endif; ?>
	            <?php endforeach; endif; ?>
            </select>
            <span class="description"><?php _e('By Selecting a specific posttype the filter will work from anywhere but only for that posttype.', 'beautiful-taxonomy-filters'); ?></span>
            </label>
        </p>
    	<?php
    }

	/**
	 * Update function for the widget
	 *
	 * @since    1.0.0
	 */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['clear_all'] = strip_tags($new_instance['clear_all']);
    	$instance['hide_empty'] = strip_tags($new_instance['hide_empty']);
    	$instance['show_count'] = strip_tags($new_instance['show_count']);
    	$instance['show_description'] = strip_tags($new_instance['show_description']);
    	$instance['post_type'] = strip_tags($new_instance['post_type']);
    	$instance['dropdown_behaviour'] = strip_tags($new_instance['dropdown_behaviour']);
        return $instance;
    }


	/**
	 * Outputs the widget with the selected settings
	 *
	 * @since    1.0.0
	 */
    public function widget( $args, $instance ) {

    	extract($args);
    	$settings = apply_filters( 'beautiful_filters_settings', get_option('beautiful_taxonomy_filters_settings') );
    	$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $clear_all = strip_tags($instance['clear_all']);
    	$hide_empty = strip_tags($instance['hide_empty']);
    	$show_count = strip_tags($instance['show_count']);
    	$post_type = strip_tags($instance['post_type']);
    	$dropdown_behaviour = strip_tags($instance['dropdown_behaviour']);
    	$activated_post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );
		$disable_select2 = (get_option('beautiful_taxonomy_filters_disable_select2') ? get_option('beautiful_taxonomy_filters_disable_select2') : false);
		$conditional_dropdowns = ( isset( $settings['conditional_dropdowns'] ) ? $settings['conditional_dropdowns'] : false );

    	//Make sure we find the current post type!
    	if($post_type == 'automatic'){

	    	$current_post_type = Beautiful_Taxonomy_Filters_Public::get_current_posttype(false);
	    	$current_post_type_rewrite = Beautiful_Taxonomy_Filters_Public::get_current_posttype(true);

    	}else{
	    	$current_post_type = $post_type;
	    	//Get the post type object
			$post_type_object = get_post_type_object($current_post_type);
			//Return the rewrite slug which is the one we actually want!
			$current_post_type_rewrite = $post_type_object->rewrite['slug'];
    	}

   		//If there is no current post type, bail early!
		if(!is_array($activated_post_types) || !$current_post_type || !in_array($current_post_type, $activated_post_types)){
			return;
		}

		//Get the taxonomies of the current post type and the excluded taxonomies
		$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') );
		//Also make sure we don't try to output the builtin taxonomies since they cannot be supported
		if(is_array($excluded_taxonomies)){
			array_push($excluded_taxonomies, 'category', 'post_tag', 'post_format');
		}else{
			$excluded_taxonomies = array(
				'category',
				'post_tag',
				'post_format'
			);
		}

		//Polylang support
		if(function_exists('pll_current_language')){
			array_push($excluded_taxonomies, 'language', 'post_translations');
		}

		$current_taxonomies = get_object_taxonomies($current_post_type, 'objects');
		//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
		if($current_taxonomies && $excluded_taxonomies){
			foreach($current_taxonomies as $key => $value){
				if(in_array($key, $excluded_taxonomies)){
					unset($current_taxonomies[$key]);
				}
			}
		}

    	//Fetch the available settings for the filter modules behaviour
    	if($clear_all == 'inherit'){
	    	$clear_all = apply_filters( 'beautiful_filters_clear_all', get_option('beautiful_taxonomy_filters_clear_all'), $current_post_type );
    	}else{
	    	$clear_all = ($clear_all == 'enable' ? 1 : 0);
	    	$clear_all = apply_filters( 'beautiful_filters_clear_all', $clear_all, $current_post_type );
    	}

    	if($hide_empty == 'inherit'){
	    	$hide_empty = apply_filters( 'beautiful_filters_hide_empty', get_option('beautiful_taxonomy_filters_hide_empty'), $current_post_type );
    	}else{
	    	$hide_empty = ($hide_empty == 'enable' ? 1 : 0);
	    	$hide_empty = apply_filters( 'beautiful_filters_hide_empty', $hide_empty, $current_post_type );
    	}

    	if($show_count == 'inherit'){
	    	$show_count = apply_filters( 'beautiful_filters_show_count', get_option('beautiful_taxonomy_filters_show_count'), $current_post_type );
    	}else{
	    	$show_count = ($show_count == 'enable' ? 1 : 0);
	    	$show_count = apply_filters( 'beautiful_filters_show_count', $show_count, $current_post_type );
    	}

    	if($dropdown_behaviour == 'inherit'){
	    	$dropdown_behaviour = apply_filters( 'beautiful_filters_dropdown_behaviour', get_option('beautiful_taxonomy_filters_dropdown_behaviour'), $current_post_type );
    	}else{
	    	$dropdown_behaviour = ($dropdown_behaviour == 'enable' ? 1 : 0);
	    	$dropdown_behaviour = apply_filters( 'beautiful_filters_dropdown_behaviour', $dropdown_behaviour, $current_post_type );
    	}


    	/*
	    * The content of the widget
	    */
        echo $before_widget;
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

		?>
		<div class="beautiful-taxonomy-filters-widget  <?php if( !$disable_select2 ){ echo 'select2-active'; } ?>" id="beautiful-taxonomy-filters-<?php echo $current_post_type; ?>">
			<?php do_action( 'beautiful_actions_before_form', $current_post_type); //Allow custom markup before form ?>
			<form method="POST" class="clearfix" id="beautiful-taxonomy-filters-form">
				<input type="hidden" name="site-url" value="<?php echo get_bloginfo('url'); ?>" />
				<input type="hidden" name="post_type_rewrite" value="<?php echo $current_post_type_rewrite; ?>" />
				<input type="hidden" name="post_type" value="<?php echo $current_post_type; ?>" />
				<?php wp_nonce_field( 'Beutiful-taxonomy-filters-do-filter', 'btf_do_filtering_nonce' ); ?>
				<?php do_action( 'beautiful_actions_beginning_form', $current_post_type); //allow custom markup at beginning of form ?>
				<?php
				//Loop through the taxonomies and output their terms in a select dropdown
				$count = count($current_taxonomies);
				$taxonomies_ordered = apply_filters('beautiful_filters_taxonomy_order', array_keys($current_taxonomies), $current_post_type);
				?>
				<div class="beautiful-taxonomy-filters-select-wrap clearfix">
					<?php do_action( 'beautiful_actions_beginning_form_inner', $current_post_type); //allow custom markup at beginning of form ?>
					<?php foreach($taxonomies_ordered as $key): ?>
						<?php
						$taxonomy = $current_taxonomies[$key];
						$terms = get_terms($key);
						?>
						<?php if(!empty($terms) && !is_wp_error($terms)): ?>
							<div class="beautiful-taxonomy-filters-tax filter-count-<?php echo $count; if($count > 5){ echo ' filter-count-many'; } ?>" id="beautiful-taxonomy-filters-tax-<?php echo $key; ?>">
								<label for="select-<?php echo $key; ?>" class="beautiful-taxonomy-filters-label"><?php echo apply_filters( 'beautiful_filters_taxonomy_label', $taxonomy->labels->name, $taxonomy->name); ?></label>
								<?php
								/**
								* Output the dropdown with the terms of the taxonomy.
								* Uses walker found in: public/class-beautiful-taxonomy-filters-walker.php
								*/
								$dropdown_args = array(
									'show_option_all' => __('All ', 'beautiful-taxonomy-filters') . $taxonomy->labels->name,
									'taxonomy'      => $key,
									'name'          => 'select-'.$key, //BUG?? For some reason we can't use the actual taxonomy slugs. If we do wordpress automatically fetches the correct posts without us even changing the URL HOWEVER it all breaks when the term has a non standard latin character in its name (not even in the slug which is what we actually use) such as åäö
									'show_count'    => $show_count,
									'hide_empty'    => $hide_empty,
									'orderby'       => apply_filters( 'beautiful_filters_dropdown_orderby', 'name', $key ),
									'order' 		=> apply_filters( 'beautiful_filters_dropdown_order', 'ASC', $key ),
									'hierarchical'  => true,
									'echo'          => 0,
									'class'			=> 'beautiful-taxonomy-filters-select',
									'walker'        => new Walker_Slug_Value_Category_Dropdown('widget', $instance)
								);
								//Apply filter on the arguments to let users modify them first!
								$dropdown_args = apply_filters( 'beautiful_filters_dropdown_categories', $dropdown_args, $taxonomy->name );

								//But if they've selected placeholder we cant use the show_option_all
								if(!$disable_select2 && $dropdown_behaviour == 'show_placeholder_option'){
									$dropdown_args['show_option_all'] = ' ';
								}

								//create the dropdown
								$filterdropdown = wp_dropdown_categories( $dropdown_args );

								//If they didnt select placeholder just output the dropdown now
								if($disable_select2 || !$dropdown_behaviour || $dropdown_behaviour == 'show_all_option'){
									echo $filterdropdown;
								}else{

									//They selected placeholder so now we need to choose what to display and then alter the dropdown before output.
									$new_label = apply_filters( 'beautiful_filters_dropdown_placeholder', __('All ', 'beautiful-taxonomy-filters') . $taxonomy->labels->name, $taxonomy->name );
									$filterdropdown = str_replace("value='0' selected='selected'", "", $filterdropdown);
									echo str_replace('<select ', '<select data-placeholder="' . $new_label . '"', $filterdropdown);
								}
								?>
								<?php if( $conditional_dropdowns ): ?>
									<span class="beautiful-taxonomy-filters-loader"><?php echo apply_filters( 'beautiful_filters_loader', sprintf( '<img src="%s" alt="" />', admin_url( 'images/spinner.gif' ) ), $key, $current_post_type ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php do_action( 'beautiful_actions_ending_form_inner', $current_post_type); //allow custom markup at end of inner form ?>
				</div>
				<?php do_action( 'beautiful_actions_before_submit_button', $current_post_type); //allow custom markup before submit button ?>
				<button type="submit" class="beautiful-taxonomy-filters-button"><?php echo apply_filters( 'beautiful_filters_apply_button', __('Apply filter', 'beautiful-taxonomy-filters') ); ?></button>
				<?php if($clear_all && is_btf_filtered() ) : ?>
					<a href="<?php echo get_post_type_archive_link($current_post_type); ?>" class="beautiful-taxonomy-filters-clear-all" title="<?php _e('Click to clear all active filters', 'beautiful-taxonomy-filters'); ?>"><?php echo apply_filters( 'beautiful_filters_clear_button', __('Clear all', 'beautiful-taxonomy-filters') ); ?></a>
				<?php endif; ?>
				<?php do_action( 'beautiful_actions_ending_form', $current_post_type); //allow custom markup at beginning of form ?>
			</form>
			<?php do_action( 'beautiful_actions_after_form', $current_post_type); //Allow custom markup after form ?>
		</div>
		<?php

		echo $after_widget;
    }
}
?>
