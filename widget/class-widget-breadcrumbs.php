<?php namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

if ( ! class_exists( 'Widget_Breadcrumbs' ) ) {

	/**
	 * Class
	 */
	class Widget_Breadcrumbs extends Widget implements Interface_Widget {

		/**
		 * Init the constructor
		 */
		function __construct() {

			$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-breadcrumbs.php' ) );

			/**
			 * Configure widget array.
			 * @var array
			 */
			$args = array(
				// Widget Backend label.
				'label'				=> __( 'ItalyStrap Breadcrumbs', 'ItalyStrap' ),
				// Widget Backend Description.
				'description'		=> __( 'Add a Breadcrumbs ti widgetized area', 'ItalyStrap' ),
				'fields'			=> $fields,
				'control_options'	=> array( 'width' => 340 ),
			 );

			/**
			 * Create Widget
			 */
			$this->create_widget( $args );
		}

		/**
		 * Dispay the widget content
		 *
		 * @param  array $args     Display arguments including 'before_title', 'after_title',
		 *                        'before_widget', and 'after_widget'.
		 * @param  array $instance The settings for the particular instance of the widget.
		 */
		public function widget_render( $args, $instance ) {

			// echo "<div>";

			// new \ItalyStrapBreadcrumbs();

			// echo "</div>";

			// $out = '';

			// return apply_filters( 'widget_text', $out );
		}
	} // class
}
