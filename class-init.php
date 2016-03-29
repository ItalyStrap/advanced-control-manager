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
	public function __construct() {
		// delete_option( 'italystrap_settings' );
		$this->options = (array) get_option( 'italystrap_settings' );
		// var_dump( $this->options );
		/**
		 * Test
		 * add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );
		 */
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
			$shortcode_carousel = new \ItalyStrap\Core\Shortcode_Carousel();
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
			register_widget( 'ItalyStrap\Core\Vcard_Widget' );
		}

		if ( isset( $this->options['post_widget'] ) ) {
			register_widget( 'ItalyStrap\Core\Widget_Posts' );
		}

		if ( isset( $this->options['media_carousel_widget'] ) ) {
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
$get_options = $init->get_options();

/**
 * Adjust priority to make sure this runs
 */
add_action( 'init', array( $init, 'on_load' ), 100 );

if ( isset( $get_options['media_carousel_shortcode'] ) ) {
	$init->add_carousel_to_gallery_shortcode();
}

/**
 * Attivate LazyLoad
 */
if ( isset( $get_options['lazyload'] ) && ! is_admin() ) {
	Lazy_Load_Image::init();
}

/**
 * Register widget
 */
add_action( 'widgets_init', array( $init, 'widgets_init' ) );

add_filter( 'widget_title', 'ItalyStrap\Core\render_html_in_title_output' );
add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );

/**
 * Get metaboxex value
 */
$post_meta = new Post_Meta;
add_action( 'wp', array( $post_meta, 'add_post_type_custom_script' ) );
add_filter( 'body_class', array( $post_meta, 'body_class' ) );
add_filter( 'post_class', array( $post_meta, 'body_class' ) );


/**
 * Set JavaScript from admin option Script
 */
ItalyStrapGlobals::set( isset( $get_options['custom_js'] ) ? $get_options['custom_js'] : '' );


/**
 * Set CSS from admin option Script
 */
ItalyStrapGlobalsCss::set( isset( $get_options['custom_css'] ) ? $get_options['custom_css'] : '' );

/**
 * Print inline css in header
 */
add_action( 'wp_head', array( $init, 'print_inline_css_in_header' ), 999999 );

/**
 * Print inline script in footer
 * Load after all and before shotdown hook
 */
add_action( 'wp_print_footer_scripts', array( $init, 'print_inline_script_in_footer' ), 999 );

/**
 * Istantiate this class only if is admin
 */
if ( is_admin() ) {

	/**
	 * Add ID to post_type table
	 */
	if ( isset( $get_options['show-ids'] ) ) {
		require( 'hooks/simply-show-ids.php' );
		add_action( 'admin_init', '\ItalyStrap\Admin\ssid_add' );
	}

	$admin_settings = (array) require( ITALYSTRAP_PLUGIN_PATH . '/admin/settings/settings-admin-page.php' );
// var_dump( $get_options );
// var_dump( $admin_settings['general']['settings_fields'][0] );
	// get_admin_default_settings( $admin_settings );
	/**
	 * Instantiate Admin Class
	 *
	 * @var object
	 */
	$admin = new \ItalyStrap\Admin\Admin( $get_options, new \ItalyStrap\Admin\Fields, $admin_settings );
	$admin->init();

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

	$register_metabox = new \ItalyStrap\Admin\Register_Metaboxes;
	add_action( 'cmb2_admin_init', array( $register_metabox, 'register_script_settings' ) );
}
