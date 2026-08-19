<?php
/**
 * Our main filter widget
 *
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/widget
 * @author     Jonathan de Jong <me@jonte.dev>
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
                'description' => esc_html__( 'Add a filter module to the sidebar', 'beautiful-taxonomy-filters' )
            ) // Args
        );
    }

    /**
	 * Admin form in the widget area
	 *
	 * @since    1.0.0
	 */
    public function form( $instance ) {

    	$title = ( !empty($instance) ? wp_strip_all_tags($instance['title']) : '' );
    	$clear_all = ( !empty($instance) ? wp_strip_all_tags($instance['clear_all']) : false );
    	$hide_empty = ( !empty($instance) ? wp_strip_all_tags($instance['hide_empty']) : false );
    	$show_count = ( !empty($instance) ? wp_strip_all_tags($instance['show_count']) : false );
    	$show_description = ( !empty($instance) ? wp_strip_all_tags($instance['show_description']) : false );
    	$post_type = ( !empty($instance) ? wp_strip_all_tags($instance['post_type']) : false );
    	$dropdown_behaviour = ( !empty($instance) ? wp_strip_all_tags($instance['dropdown_behaviour']) : false );
    	?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'beautiful-taxonomy-filters'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
    	<p>
            <label for="<?php echo esc_attr( $this->get_field_id('clear_all') ); ?>"><?php esc_html_e('Enable a "clear all" link: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('clear_all') ); ?>" name="<?php echo esc_attr( $this->get_field_name('clear_all') ); ?>">
            	<option value="inherit" <?php if($clear_all == 'inherit' || !$clear_all){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($clear_all == 'enable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($clear_all == 'disable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>"><?php esc_html_e('Hide empty terms: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('hide_empty') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hide_empty') ); ?>">
            	<option value="inherit" <?php if($hide_empty == 'inherit' || !$hide_empty){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($hide_empty == 'enable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($hide_empty == 'disable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_count') ); ?>"><?php esc_html_e('Show post count: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_count') ); ?>">
            	<option value="inherit" <?php if($show_count == 'inherit' || !$show_count){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($show_count == 'enable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($show_count == 'disable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('show_description') ); ?>"><?php esc_html_e('Show term description: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_description') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_description') ); ?>">
            	<option value="inherit" <?php if($show_description == 'inherit' || !$show_description){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="enable" <?php if($show_description == 'enable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Enable', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="disable" <?php if($show_description == 'disable'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Disable', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('dropdown_behaviour') ); ?>"><?php esc_html_e('Dropdown deselect/default behaviour:', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('dropdown_behaviour') ); ?>" name="<?php echo esc_attr( $this->get_field_name('dropdown_behaviour') ); ?>">
            	<option value="inherit" <?php if($dropdown_behaviour == 'inherit' || !$dropdown_behaviour){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Inherit', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="show_all_option" <?php if($dropdown_behaviour == 'show_all_option'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('All option', 'beautiful-taxonomy-filters'); ?></option>
            	<option value="show_placeholder_option" <?php if($dropdown_behaviour == 'show_placeholder_option'){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Placeholder', 'beautiful-taxonomy-filters'); ?></option>
            </select>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('post_type') ); ?>"><?php esc_html_e('Specific posttype: ', 'beautiful-taxonomy-filters'); ?>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('post_type') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post_type') ); ?>">
            	<option value="automatic" <?php if($show_count == 'automatic' || !$post_type){ echo esc_attr( 'selected' ); } ?>><?php esc_html_e('Automatic (default)', 'beautiful-taxonomy-filters'); ?></option>
            	<?php
	            $post_types = get_post_types(
					array(
						'public' => true,
						'_builtin' => false
					),
					'objects'
				);
				$activated_post_types = (array) apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types', [] ) );
	            ?>
	            <?php if($post_types): foreach($post_types as $single_post_type): ?>
	            	<?php if(in_array($single_post_type->name, $activated_post_types)): ?>
		            	<option value="<?php echo esc_attr( $single_post_type->name ); ?>" <?php if($post_type == $single_post_type->name){ echo esc_attr( 'selected' ); } ?>><?php echo esc_html( $single_post_type->labels->name ); ?></option>
		            <?php endif; ?>
	            <?php endforeach; endif; ?>
            </select>
            <span class="description"><?php esc_html_e('By Selecting a specific posttype the filter will work from anywhere but only for that posttype.', 'beautiful-taxonomy-filters'); ?></span>
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
			$instance['title'] = wp_strip_all_tags($new_instance['title']);
			$instance['clear_all'] = wp_strip_all_tags($new_instance['clear_all']);
			$instance['hide_empty'] = wp_strip_all_tags($new_instance['hide_empty']);
			$instance['show_count'] = wp_strip_all_tags($new_instance['show_count']);
			$instance['show_description'] = wp_strip_all_tags($new_instance['show_description']);
			$instance['post_type'] = wp_strip_all_tags($new_instance['post_type']);
			$instance['dropdown_behaviour'] = wp_strip_all_tags($new_instance['dropdown_behaviour']);
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
        $clear_all = wp_strip_all_tags($instance['clear_all']);
    	$hide_empty = wp_strip_all_tags($instance['hide_empty']);
    	$show_count = wp_strip_all_tags($instance['show_count']);
    	$post_type = wp_strip_all_tags($instance['post_type']);
    	$dropdown_behaviour = wp_strip_all_tags($instance['dropdown_behaviour']);
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
        echo $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		?>
		<div class="beautiful-taxonomy-filters-widget  <?php if( !$disable_select2 ){ echo esc_attr( 'select2-active' ); } ?>" id="beautiful-taxonomy-filters-<?php echo esc_attr( $current_post_type ); ?>">
			<?php do_action( 'beautiful_actions_before_form', $current_post_type); //Allow custom markup before form ?>
			<form method="POST" class="clearfix" id="beautiful-taxonomy-filters-form">
				<input type="hidden" name="site-url" value="<?php echo esc_url( get_bloginfo('url') ); ?>" />
				<input type="hidden" name="post_type_rewrite" value="<?php echo esc_attr( $current_post_type_rewrite ); ?>" />
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $current_post_type ); ?>" />
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
							<div class="beautiful-taxonomy-filters-tax filter-count-<?php echo esc_attr( $count ); if($count > 5){ echo esc_attr( ' filter-count-many' ); } ?>" id="beautiful-taxonomy-filters-tax-<?php echo esc_attr( $key ); ?>">
								<label for="select-<?php echo esc_attr( $key ); ?>" class="beautiful-taxonomy-filters-label"><?php echo esc_html( apply_filters( 'beautiful_filters_taxonomy_label', $taxonomy->labels->name, $taxonomy->name) ); ?></label>
								<?php
								/**
								* Output the dropdown with the terms of the taxonomy.
								* Uses walker found in: public/class-beautiful-taxonomy-filters-walker.php
								*/
								$dropdown_args = array(
									'show_option_all' => $taxonomy->labels->all_items,
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
									echo $filterdropdown; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}else{

									//They selected placeholder so now we need to choose what to display and then alter the dropdown before output.
									$new_label = apply_filters( 'beautiful_filters_dropdown_placeholder', esc_html__('All ', 'beautiful-taxonomy-filters') . $taxonomy->labels->name, $taxonomy->name );
									$filterdropdown = str_replace("value='0' selected='selected'", "", $filterdropdown);
									echo str_replace('<select ', '<select data-placeholder="' . $new_label . '"', $filterdropdown); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
								<?php if( $conditional_dropdowns ): ?>
									<span class="beautiful-taxonomy-filters-loader">
										<?php
										echo apply_filters( 'beautiful_filters_loader', sprintf( '<img src="%s" alt="" />', admin_url( 'images/spinner.gif' ) ), $key, $current_post_type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php do_action( 'beautiful_actions_ending_form_inner', $current_post_type); //allow custom markup at end of inner form ?>
				</div>
				<?php do_action( 'beautiful_actions_before_submit_button', $current_post_type); //allow custom markup before submit button ?>
				<button type="submit" class="beautiful-taxonomy-filters-button"><?php echo esc_html( apply_filters( 'beautiful_filters_apply_button', esc_html__('Apply filter', 'beautiful-taxonomy-filters') ) ); ?></button>
				<?php if($clear_all && is_btf_filtered() ) : ?>
					<a href="<?php echo esc_url( apply_filters( 'beautiful_filters_clear_all_link', get_post_type_archive_link( $current_post_type ), $current_post_type ) ); ?>" class="beautiful-taxonomy-filters-clear-all" title="<?php esc_html_e('Click to clear all active filters', 'beautiful-taxonomy-filters'); ?>"><?php echo esc_html( apply_filters( 'beautiful_filters_clear_button', esc_html__('Clear all', 'beautiful-taxonomy-filters') ) ); ?></a>
				<?php endif; ?>
				<?php do_action( 'beautiful_actions_ending_form', $current_post_type); //allow custom markup at beginning of form ?>
			</form>
			<?php do_action( 'beautiful_actions_after_form', $current_post_type); //Allow custom markup after form ?>
		</div>
		<?php

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
?>
