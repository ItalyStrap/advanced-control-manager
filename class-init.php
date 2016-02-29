<?php namespace ItalyStrap\Core;
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

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
	public function __construct() {

		$this->options = get_option( 'italystrap_settings' );

		// /**
		//  * Adjust priority to make sure this runs
		//  */
		// add_action( 'init', array( $this, 'on_load' ), 100 );

		/**
		 * Print inline css in header
		 */
		add_action( 'wp_head', array( $this, 'print_inline_css_in_header' ), 999 );

		/**
		 * Print inline script in footer
		 * Load after all and before shotdown hook
		 */
		add_action( 'wp_print_footer_scripts', array( $this, 'print_inline_script_in_footer' ), 999 );

		if ( isset( $this->options['lazyload'] ) && ! is_admin() ) {
			Lazy_Load_Image::init();
		}

		// add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );

	}

	/**
	 * Init functions
	 */
	public function on_load() {

		/**
		 * Load po file
		 */
		load_plugin_textdomain( 'ItalyStrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );

		/**
		 * Istantiate ItalyStrapCarouselLoader only if [gallery] shortcode exist
		 *
		 * @link http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue
		 */
		$post = get_post();
		$gallery = false;

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'gallery' ) ) {
			$gallery = true; // A http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/ .
		}

		if ( ! $gallery ) {
			new \ItalyStrap\Core\ItalyStrapCarouselLoader();
		}

	}

	/**
	 * Add action to widget_init
	 * Initialize widget
	 */
	public function widgets_init() {

		if ( isset( $this->options['vcardwidget'] ) ) {
			register_widget( 'ItalyStrap\Core\Vcard_Widget' );
		}

		if ( isset( $this->options['post_widget'] ) ) {
			register_widget( 'ItalyStrap\Core\Widget_Posts' );
		}

		if ( isset( $this->options['media_widget'] ) ) {
			register_widget( 'ItalyStrap\Core\Widget_Media_Carousel' );
		}

		register_widget( 'ItalyStrap\Core\Widget_Breadcrumbs' );
		register_widget( 'ItalyStrap\Core\Widget_VCard' );
		register_widget( 'ItalyStrap\Core\Widget_Posts2' );
		register_widget( 'ItalyStrap\Core\Widget_Product' );
		register_widget( 'ItalyStrap\Core\Widget_Image' );

	}

	/**
	 * Print inline script in footer after all and before shotdown hook.
	 *
	 * @todo Creare un sistema che appenda regolarmente dopo gli script
	 *       e tenga presente delle dipendenze da jquery
	 */
	public function print_inline_script_in_footer() {

		$scipt = ItalyStrapGlobals::get();

		if ( $scipt ) { echo '<script type="text/javascript">/*<![CDATA[*/' . esc_js( $scipt ) . '/*]]>*/</script>';
		} else { echo ''; }

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
 * New instance of Italystrap Init
 *
 * @var Init
 */
$init = new Init;

/**
 * Adjust priority to make sure this runs
 */
add_action( 'init', array( $init, 'on_load' ), 100 );

/**
 * Register widget
 */
// $init->widgets_init();

add_action( 'widgets_init', array( $init, 'widgets_init' ) );

add_filter( 'widget_title', 'ItalyStrap\Core\render_html_in_title_output' );
add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );

/**
 * Add ID to post_type table
 */
require( 'hooks/simply-show-ids.php' );
add_action( 'admin_init', '\ItalyStrap\Admin\ssid_add' );

/**
 * Istantiate this class only if is admin
 */
if ( is_admin() ) {

	new \ItalyStrapAdmin;

	new \ItalyStrapAdminGallerySettings;

	/**
	 * Da testare
	 *
	 * Example: new \ItalyStrap\Admin\Gallery_Settings;
	 * $gallery_settings = new Gallery_Settings;
	 * add_action( 'admin_init', array( $gallery_settings, 'admin_init' ) );
	 */

	$image_size_media = new \ItalyStrapAdminMediaSettings;
	add_filter( 'image_size_names_choose', array( $image_size_media, 'get_image_sizes' ), 999 );
}
