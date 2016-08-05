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

		$widget_list = array(
			'vcardwidget'			=> 'Vcard_Widget', // Deprecated
			'post_widget'			=> 'Widget_Posts2', // Deprecated
			'widget_post'			=> 'Widget_Posts',
			'media_carousel_widget'	=> 'Widget_Media_Carousel',
			'widget_vcard'			=> 'Widget_VCard', // New
		);

		foreach ( $widget_list as $key => $value ) {
			if ( isset( $this->options[ $key ] ) ) {
				\register_widget( 'ItalyStrap\Core\\' . $value );
			}
		}

		if ( defined( 'ITALYSTRAP_BETA' ) ) {

			if ( isset( $this->options['widget_image'] ) ) {
				\register_widget( 'ItalyStrap\Core\Widget_Image' );
			}

			// \register_widget( 'ItalyStrap\Core\Widget_Breadcrumbs' );

			if ( isset( $this->options['widget_product'] ) ) {
				$widget_product = new Widget_Product;
				// register_widget( 'ItalyStrap\Core\Widget_Product' );
				register_widget( $widget_product );
			}

			\register_widget( 'ItalyStrap\Core\Widget_Taxonomies_Posts' );

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

/**
 * This are some functionality in beta version.
 * If you want to use thoose functionality you have to define ITALYSTRAP_BETA
 * constant in your wp-config.php first.
 * Also remember that you do it at own risk.
 * If you are not shure don't do it ;-)
 */
if ( defined( 'ITALYSTRAP_BETA' ) ) {

	/**
	 * Instantiate Customizer_Manager Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Customizer_Manager
	 */
	$widget_areas = $injector->make( 'ItalyStrap\Widget\Widget_Areas' );
	$widget_areas->register_sidebars();
	add_action( 'init', array( $widget_areas, 'register_post_type' ), 20 );
	add_action( 'save_post', array( $widget_areas, 'add_sidebar' ), 10, 3 );
	add_action( 'edit_post', array( $widget_areas, 'add_sidebar' ), 10, 2 );
	add_action( 'delete_post', array( $widget_areas, 'delete_sidebar' ) );
	// delete_option( 'italystrap_widget_area' );
	// d( get_option( 'italystrap_widget_area' ) );

}
