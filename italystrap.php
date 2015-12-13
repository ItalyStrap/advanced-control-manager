<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website more powerfull. | <a href="admin.php?page=italystrap-documentation">Documentation</a>
 *	Version:		1.3.3
 *	Author:			Enea Overclokk
 *	Author URI:		http://www.overclokk.net
 *	Text Domain:	ItalyStrap
 *	License:		GPLv2 or later
 *	License URI:	http://www.gnu.org/licenses/gpl-2.0.html
 *	Domain Path:	/lang
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

use \ItalyStrap\Core\Widget_Posts;
use \ItalyStrap\Core\Widget_Media_Carousel;
use \ItalyStrap\Core\Vcard_Widget;
use \ItalyStrap\Core\ItalyStrapCarouselLoader;
use \ItalyStrap\Core\MV_My_Recent_Posts_Widget;

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if ( ! defined( 'WPINC' ) )
	die;

/**
 * Define some costant for internal use
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN' ) )
	define( 'ITALYSTRAP_PLUGIN', true );

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended\italystrap.php
 */
if ( ! defined( 'ITALYSTRAP_FILE' ) )
	define( 'ITALYSTRAP_FILE', __FILE__ );

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN_PATH' ) )
	define( 'ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( ITALYSTRAP_FILE ) );

/**
 * Example: 'http://192.168.1.10/italystrap/wp-content/plugins/italystrap-extended/'
 */
if ( ! defined( 'ITALYSTRAP_PLUGIN_URL' ) )
	define( 'ITALYSTRAP_PLUGIN_URL', plugin_dir_url( ITALYSTRAP_FILE ) );

/**
 * Example = italystrap-extended/italystrap.php
 */
if ( ! defined( 'ITALYSTRAP_BASENAME' ) )
	define( 'ITALYSTRAP_BASENAME', plugin_basename( ITALYSTRAP_FILE ) );

/**
 * Require PHP autoload
 */
require( ITALYSTRAP_PLUGIN_PATH . 'vendor/autoload.php' );

/**
 * Require Debug file
 */
require( ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php' );

/**
 * Initialize class
 */

if ( ! class_exists( 'ItalyStrapInit' ) ) {

	/**
	 * Init Class fo r ItalyStrap core
	 */
	class ItalyStrapInit {

		/**
		 * The plugin's options
		 * @var string
		 */
		private $options = '';

		/**
		 * Fire the construct
		 */
		public function __construct() {

			$this->options = get_option( 'italystrap_settings' );

			/**
			 * Istantiate this class only if is admin
			 */
			if ( is_admin() ) {

				new ItalyStrapAdmin;
				new ItalyStrapAdminGallerySettings;

				$image_size_media = new ItalyStrapAdminMediaSettings;
				add_filter( 'image_size_names_choose', array( $image_size_media, 'get_image_sizes' ), 999 );
			}

			/**
			 * Adjust priority to make sure this runs
			 */
			add_action( 'init', array( $this, 'italystrap_init' ), 100 );

			/**
			 * Print inline css in header
			 */
			add_action( 'wp_head', array( $this, 'italystrap_print_inline_css_in_header' ), 999 );

			/**
			 * Print inline script in footer
			 * Load after all and before shotdown hook
			 */
			add_action( 'wp_print_footer_scripts', array( $this, 'italystrap_print_inline_script_in_footer' ), 999 );

			if ( isset( $this->options['lazyload'] ) && ! is_admin() )
				ItalyStrapLazyload::init();

			if ( isset( $this->options['vcardwidget'] ) )
				add_action( 'widgets_init', function() {
					register_widget( 'ItalyStrap\Core\Vcard_Widget' );
				});

			if ( isset( $this->options['post_widget'] ) )
				add_action( 'widgets_init', function() {
					register_widget( 'ItalyStrap\Core\Widget_Posts' );
				});

			if ( isset( $this->options['media_widget'] ) )
				add_action( 'widgets_init', function() {
					register_widget( 'ItalyStrap\Core\Widget_Media_Carousel' );
				});

				add_action( 'widgets_init', function() {
					register_widget( 'ItalyStrap\Core\MV_My_Recent_Posts_Widget' );
				});

		}

		/**
		 * Init functions
		 */
		public function italystrap_init() {

			/**
			 * Load po file
			 */
			load_plugin_textdomain( 'ItalyStrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );

			/**
			 * Istantiate ItalyStrapCarouselLoader only if [gallery] shortcode exist
			 * @link http://wordpress.stackexchange.com/questions/103549/wp-deregister-register-and-enqueue-dequeue
			 */
			$post = get_post();
			$gallery = false;

			if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'gallery' ) )
				$gallery = true; // A http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/ .

			if ( ! $gallery )
				new \ItalyStrap\Core\ItalyStrapCarouselLoader();

		}

		/**
		 * Print inline script in footer after all and before shotdown hook.
		 *
		 * @todo Creare un sistema che appenda regolarmente dopo gli script
		 *       e tenga presente delle dipendenze da jquery
		 */
		public function italystrap_print_inline_script_in_footer() {

			$scipt = ItalyStrapGlobals::get();

			if ( $scipt ) echo '<script type="text/javascript">/*<![CDATA[*/' . $scipt . '/*]]>*/</script>';

			else echo '';

		}

		/**
		 * Print inline css.
		 */
		public function italystrap_print_inline_css_in_header() {

			$css = ItalyStrapGlobalsCss::get();

			if ( $css ) echo '<style>' . $css . '</style>';

			else echo '';
		}
	} // End ItalyStrapInit

	new ItalyStrapInit;
}

/**
 * Istantiate Mobile_Detect class for responive use
 * @todo Passare l'istanza dentro la classe http://stackoverflow.com/a/10634148
 * @var obj
 */
// $detect = new \Detection\MobileDetect;
$detect = new Mobile_Detect;
