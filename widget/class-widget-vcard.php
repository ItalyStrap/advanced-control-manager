<?php
/**
 * Class for new vCard widget
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Da leggere https://carlalexander.ca/polymorphism-wordpress-interfaces/
 */

/**
 * Link utili
 *
 * @link http://codex.wordpress.org/Function_Reference/the_widget
 * @link https://core.trac.wordpress.org/browser/tags/3.9.2/src/wp-includes/default-widgets.php#L0
 */

/**
 * Widget vCard Local Business
 *
 * @todo Controllare i vari link Schema.org, qualcuno potrebbe dare errore
 * @todo Mettere lista opzioni in ordine alfabetico con jquery
 * @todo Aggiornare la lista completa delle attività di local business
 * @todo Aggiungere la lista delle attività al file readme
 * @todo Ordinare la lista per dipendenze
 *       store
 *       store - musicstore
 * @todo creare array nome -> valore con lista di tutte le attività
 * @todo https://wordpress.org/plugins/mdp-local-business-seo/
 * @todo https://wordpress.org/plugins/local-business-microdata-widget/
 * @todo https://wordpress.org/plugins/local-search-seo-contact-page/
 * @todo search local seo
 *
 * Added upload media library for image
 * @link http://www.paulund.co.uk/add-upload-media-library-widgets
 */
if ( ! class_exists( 'Widget_VCard' ) ) {

	/**
	 * Class
	 */
	class Widget_VCard extends Widget {

		/**
		 * Init the constructor
		 */
		function __construct() {

			$fields = array_merge( $this->title_field(), require( ITALYSTRAP_PLUGIN_PATH . 'options/options-vcard.php' ) );

			/**
			 * Configure widget array.
			 *
			 * @var array
			 */
			$args = array(
				// Widget Backend label.
				'label'				=> __( 'ItalyStrap vCard Local Business', 'ItalyStrap' ),
				// Widget Backend Description.
				'description'		=> __( 'Add a vCard Local Business with Schema.org markup to your theme widgetized area', 'ItalyStrap' ),
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
			/**
			 * var_dump( get_option( 'widget_italystrap-vcard-local-business' ) );
			 */

			ob_start();

			require( ITALYSTRAP_PLUGIN_PATH . 'templates/content-vcard.php' );

			$out = ob_get_contents();
			ob_end_clean();

			return $out;

		}
	} // Class.
}
