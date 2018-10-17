<?php

/**
 * The shortcode functionality of the plugin.
 *
 * @link       http://github.com/jonathandejong
 * @since      1.0.0
 *
 * @package    Beautiful_Taxonomy_Filters
 * @subpackage Beautiful_Taxonomy_Filters/includes
 * @author     Jonathan de Jong <jonathan@tigerton.se>
 */

class Beautiful_Taxonomy_Filters_Shortcodes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name    = $name;
		$this->version = $version;

		add_shortcode( 'show_beautiful_filters', array( $this, 'shortcode_beautiful_filters' ) );
		add_shortcode( 'show_beautiful_filters_info', array( $this, 'shortcode_beautiful_filters_info' ) );

	}


	/**
	 * Shortcode function for returning a BTF filter module.
	 *
	 * @since 2.3.5
	 * @param  array $attributes shortcode attributes
	 */
	public function shortcode_beautiful_filters( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'post_type' => false,
			),
			$attributes,
			'show_beautiful_filters'
		);
		return Beautiful_Taxonomy_Filters_Public::beautiful_filters( $attributes['post_type'], array( 'echo' => false ) );

	}


	/**
	 * Shortcode function for returning a BTF filter info module.
	 *
	 * @since 2.3.5
	 */
	public function shortcode_beautiful_filters_info() {
		return Beautiful_Taxonomy_Filters_Public::beautiful_filters_info();

	}


}
