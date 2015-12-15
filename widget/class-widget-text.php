<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

// da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/

if ( ! class_exists( 'MV_My_Recent_Posts_Widget' ) ) {

	/**
	 * Class
	 */
	class MV_My_Recent_Posts_Widget extends Widget implements Interface_Widget {

		/**
		 * [__construct description]
		 */
		function __construct() {

			/**
			 * Instance of list of image sizes
			 * @var ItalyStrapAdminMediaSettings
			 */
			$image_size_media = new ItalyStrapAdminMediaSettings;
			$image_size_media_array = $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'ItalyStrap' ) ) );

			/**
			 * Configure widget array.
			 * @var array
			 */
			$args = array(
				// Widget Backend label.
				'label'				=> __( 'My Recent Posts', 'ItalyStrap' ),
				// Widget Backend Description.
				'description'		=> __( 'My Recent Posts Widget Description', 'ItalyStrap' ),
				'fields'			=> require( ITALYSTRAP_PLUGIN_PATH . 'options/options-test.php' ),
				'control_options'	=> array( 'width' => 340 ),
			 );

			$title_field = array(
				'title'	=> array(
					'name'		=> __( 'Title', 'ItalyStrap' ),
					'desc'		=> __( 'Enter the widget title.', 'ItalyStrap' ),
					'id'		=> 'title',
					'type'		=> 'text',
					'class'		=> 'widefat',
					'default'	=> 'My Recent Posts',
					'validate'	=> 'alpha_dash',
					'filter'	=> 'strip_tags|esc_attr',
					 ),
				);

			$args['fields'] = array_merge( $title_field, $args['fields'] );

			/**
			 * Create Widget
			 */
			$this->create_widget( $args );
		}

		/**
		 * [my_custom_validation description]
		 * @param  int $value
		 * @return bol
		 */
		function my_custom_validation( $value ) {
			if ( strlen( $value ) > 1 )
				return false;
			else return true;
		}

		/**
		 * [widget description]
		 * @param  [type] $args     [description]
		 * @param  [type] $instance [description]
		 * @return [type]           [description]
		 */
		function widget( $args, $instance ) {

			$out  = $args['before_title'];
			$out .= $instance['title'];
			$out .= $args['after_title'];

			$out .= '<p>Hey There! </p>';

			echo apply_filters( 'widget_text', $out );
		}
	} // class
}
