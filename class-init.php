<?php
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Init Class for ItalyStrap Core
 */
class Init {

	/**
	 * The plugin's options
	 *
	 * @var string
	 */
	private $options = '';

	/**
	 * Fire the construct
	 */
	public function __construct( array $options = array() ) {
		// delete_option( 'italystrap_settings' );
		$this->options = $options;
		// $this->options = (array) get_option( 'italystrap_settings' );
		// var_dump( $this->options );
	}

	/**
	 * Get Options
	 *
	 * @since 2.0.0
	 * @return array        Get options
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Init functions
	 */
	public function on_load() {

		/**
		 * Load po file
		 */
		load_plugin_textdomain( 'ItalyStrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );
	}

	/**
	 * Add type Carousel to built-in gallery shortcode
	 */
	public function add_carousel_to_gallery_shortcode() {
	
		/**
		 * Istantiate Shortcode_Carousel only if [gallery] shortcode exist
		 *
		 * @link http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue
		 */
		$post = get_post();
		$gallery = false;

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'gallery' ) ) {
			$gallery = true; // A http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/ .
		}
// d($gallery);
		if ( ! $gallery ) {
			$shortcode_carousel = new \ItalyStrap\Shortcode\Carousel();
			add_filter( 'post_gallery', array( $shortcode_carousel, 'gallery_shortcode' ), 10, 4 );
			add_filter( 'jetpack_gallery_types', array( $shortcode_carousel, 'gallery_types' ) );
			// add_filter( 'ItalyStrap_gallery_types', array( $shortcode_carousel, 'gallery_types' ), 999 );
		}
	
	}

	/**
	 * Add action to widget_init
	 * Initialize widget
	 */
	public function widgets_init() {

		if ( isset( $this->options['vcardwidget'] ) ) {
			\register_widget( 'ItalyStrap\Core\Vcard_Widget' );
		}

		if ( isset( $this->options['post_widget'] ) ) {
			\register_widget( 'ItalyStrap\Core\Widget_Posts' );
		}

		if ( isset( $this->options['media_carousel_widget'] ) ) {
			\register_widget( 'ItalyStrap\Core\Widget_Media_Carousel' );
		}

		\register_widget( 'ItalyStrap\Core\Widget_Breadcrumbs' );
		\register_widget( 'ItalyStrap\Core\Widget_VCard' );
		\register_widget( 'ItalyStrap\Core\Widget_Posts2' );

		if ( isset( $this->options['widget_product'] ) ) {
			$widget_product = new Widget_Product;
			// register_widget( 'ItalyStrap\Core\Widget_Product' );
			register_widget( $widget_product );
		}

		if ( isset( $this->options['widget_image'] ) ) {
			\register_widget( 'ItalyStrap\Core\Widget_Image' );
		}

	}

	/**
	 * Print inline script in footer after all and before shotdown hook.
	 *
	 * @todo Creare un sistema che appenda regolarmente dopo gli script
	 *       e tenga presente delle dipendenze da jquery
	 */
	public function print_inline_script_in_footer() {

		$script = ItalyStrapGlobals::get();

		if ( ! $script ) {
			return;
		}

		// echo '<script type="text/javascript">/*<![CDATA[*/' . json_encode( $script ) . '/*]]>*/</script>';
		echo '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';

	}

	/**
	 * Print inline css.
	 */
	public function print_inline_css_in_header() {

		$css = ItalyStrapGlobalsCss::get();

		if ( $css ) { echo '<style>' . esc_attr( $css ) . '</style>';
		} else { echo ''; }
	}
} // End Init
