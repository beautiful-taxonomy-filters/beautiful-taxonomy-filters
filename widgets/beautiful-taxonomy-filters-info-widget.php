<?php
/**
 * Our main filter widget
 *
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/widget
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */
class Beautiful_Taxonomy_Filters_Info_Widget extends WP_Widget {
 
 
 	/**
	 * Constructor for the widget
	 *
	 * @since    1.1.0
	 */
    public function __construct() {
        parent::__construct(
            'beautiful-taxonomy-filters-info-widget', // Base ID
            'Beautiful Active Filter info', // Name
            array(
                'description' => __( 'Add an active filter info module to the sidebar', 'beautiful-taxonomy-filters' )
            ) // Args
        );
    }
    
    /**
	 * Admin form in the widget area
	 *
	 * @since    1.0.0
	 */
    public function form( $instance ) {
    	$title = strip_tags($instance['title']);
    	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
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
        return $instance;
    }
    
    
	/**
	 * Outputs the widget with the selected settings
	 *
	 * @since    1.0.0
	 */
    public function widget( $args, $instance ) {
	    
    	extract($args);
    	$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		global $wp_query;
		if(!empty($wp_query->tax_query->queries)){
			$current_taxonomies = $wp_query->tax_query->queries;	
		}else{
			$current_taxonomies = false;
		}
		$hide_postcount = apply_filters( 'beautiful_filters_disable_postcount', get_option('beautiful_taxonomy_filters_disable_postcount') );
		$activated_post_types = apply_filters( 'beautiful_filters_post_types', get_option('beautiful_taxonomy_filters_post_types') );
		$current_post_type = Beautiful_Taxonomy_Filters_Public::get_current_posttype(false);
		
		//If there is no current post type, bail early!
		if(!is_array($activated_post_types) || !$current_post_type || !in_array($current_post_type, $activated_post_types)){
			return;
		}
		
		/*
	    * The content of the widget
	    */
        echo $before_widget;
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
		?>
		<div class="beautiful-taxonomy-filters-active-filter-widget">
			<?php if(!$hide_postcount): ?>
				<p class="beautiful-taxonomy-filters-postcount"><?php echo apply_filters( 'beautiful_filters_info_postcount', sprintf( __( 'Result of filter: %d', 'beautiful-taxonomy-filters' ), $wp_query->found_posts ) ); ?></p>
				
			<?php endif; ?>
			<?php $posttypes_taxonomies = get_object_taxonomies($current_post_type, 'objects'); ?>
			<?php if($current_taxonomies): ?>
				<?php 
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
				//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
				if($current_taxonomies && $excluded_taxonomies){
					foreach($current_taxonomies as $key => $value){
						if(in_array($value['taxonomy'], $excluded_taxonomies)){
							unset($current_taxonomies[$key]);
						}
					}
				}
				foreach($posttypes_taxonomies as $key => $value){
					if(in_array($key, $excluded_taxonomies)){
						unset($posttypes_taxonomies[$key]);
					}
				}
				?>
				<?php foreach($current_taxonomies as $taxonomy): ?>
					<?php
					if(array_key_exists($taxonomy['taxonomy'], $posttypes_taxonomies)){
						unset($posttypes_taxonomies[$taxonomy['taxonomy']]);
					}
					?>
					<div class="beautiful-taxonomy-filters-single-tax">
						<?php
						//get the taxonomy object
						$taxonomy_info = get_taxonomy($taxonomy['taxonomy']);
						//Get the terms objects
						$terms = get_terms($taxonomy['taxonomy']);
						//setup an empty array for the actual terms we want.
						$active_terms = array();
						if(!is_wp_error($terms)){
							foreach($terms as $term){
								//If the term slugs matches, put it in the array!
								if(in_array($term->slug, $taxonomy['terms'])){
									$active_terms[$term->term_id] = $term->name;
								}
							}
						}
						//Implode that sh**t!
						$imploded_terms = implode(', ', $active_terms);
						$label = $taxonomy_info->labels->name . ':';
						?>
						<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy['taxonomy']); ?></span>
						<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $imploded_terms, $taxonomy['taxonomy']); ?></span>
					</div>
				<?php endforeach; ?>
				<?php if(!empty($posttypes_taxonomies)): foreach($posttypes_taxonomies as $taxonomy): ?>
					<div class="beautiful-taxonomy-filters-single-tax">
						<?php
						$label = $taxonomy->labels->name . ':';
						$value = $taxonomy->labels->all_items; 
						?>
						<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy->query_var); ?></span>
						<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $value, $taxonomy->query_var); ?></span>
					</div>
				<?php endforeach; endif; ?>
				
			<?php else: ?>
				
				<?php
				//Get the taxonomies of the current post type and the excluded taxonomies
				$excluded_taxonomies = apply_filters( 'beautiful_filters_taxonomies', get_option('beautiful_taxonomy_filters_taxonomies') );
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
				//If we both have taxonomies on the post type AND we've set som excluded taxonomies in the plugins settings. Loop through them and unset those we don't want!
				if($posttypes_taxonomies && $excluded_taxonomies){
					foreach($posttypes_taxonomies as $key => $value){
						if(in_array($key, $excluded_taxonomies)){
							unset($posttypes_taxonomies[$key]);
						}
					}
				}
				?>
				<?php if($posttypes_taxonomies): ?>
			
					<?php foreach($posttypes_taxonomies as $taxonomy): ?>
						<div class="beautiful-taxonomy-filters-single-tax">
							<?php
							$label = $taxonomy->labels->name . ':';
							$value = $taxonomy->labels->all_items;  
							?>
							<span class="single-tax-key"><?php echo apply_filters('beautiful_filters_active_taxonomy', $label, $taxonomy->query_var); ?></span>
							<span class="single-tax-value"><?php echo apply_filters('beautiful_filters_active_terms', $value, $taxonomy->query_var); ?></span>
						</div>
					<?php endforeach; ?>
					
				<?php endif; ?>
			
			<?php endif; ?>
		</div>

		<?php
        
		echo $after_widget;
    }
}
?>