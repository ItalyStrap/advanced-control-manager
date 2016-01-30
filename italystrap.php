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
use \ItalyStrap\Core\Query_Posts;

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
if ( is_admin() ) require( ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php' );

require( ITALYSTRAP_PLUGIN_PATH . 'functions/general-functions.php' );

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
					register_widget( 'ItalyStrap\Core\Widget_Breadcrumbs' );
					register_widget( 'ItalyStrap\Core\Widget_VCard' );
					register_widget( 'ItalyStrap\Core\Widget_Posts2' );
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

function test_carousel_posts() {

	$atts = array();

	// $atts['ids'] = '1045,2051,13,12,1177,16,1163';
	$atts['type'] = 'carousel';
	$atts['name'] = 'prova';
	$atts['size'] = 'large';

	// $atts['ids'] = array(
	// 	'id'	=> 'ids',
	// 	'default'	=> '1777,1016,1011',
	// 	);
	// $atts['type'] = array(
	// 	'id'		=> 'type',
	// 	'default'	=> 'carousel',
	// 	);

	// 'ids'				=> array(
	// 			'name'		=> __( 'Images ID', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter the image ID.', 'ItalyStrap' ),
	// 			'id'		=> 'ids',
	// 			'type'		=> 'media_list',
	// 			'class'		=> 'widefat ids',
	// 			'default'	=> false,
	// 			// 'validate'	=> 'numeric_comma',
	// 			'filter'	=> 'sanitize_text_field',
	// 			 ),

	// /**
	//  * Type of gallery. If it's not "carousel", nothing will be done.
	//  */
	// 'type'				=> array(
	// 			'name'		=> __( 'Type of gallery', 'ItalyStrap' ),
	// 			'desc'		=> __( 'Enter the type of gallery, if it\'s not "carousel", nothing will be done.', 'ItalyStrap' ),
	// 			'id'		=> 'type',
	// 			'type'		=> 'select',
	// 			'class'		=> 'widefat',
	// 			'class-p'	=> 'hidden',
	// 			'default'	=> 'carousel',
	// 			'options'	=> array(
	// 						'standard'  => __( 'Standard Gallery', 'ItalyStrap' ),
	// 						'carousel'  => __( 'Carousel (Default)', 'ItalyStrap' ),
	// 			 			),
	// 			'validate'	=> 'alpha_numeric',
	// 			'filter'	=> 'sanitize_text_field',
	// 			 ),

	$carousel_posts = new \ItalyStrap\Core\Carousel_Posts( $atts );
	// var_dump( $carousel_posts->validate_data() );
	// var_dump( $carousel_posts->__get( 'output' ) );
	echo $carousel_posts->__get( 'output' );

}
// add_action( 'content_container_open', 'test_carousel_posts' );
add_action( 'single', 'test_carousel_posts' );

// Add Shortcode
function query_posts_shortcode( $atts , $content = null ) {

	// Attributes
	// extract( shortcode_atts(
	// 	array(
	// 		'posts' => '5',
	// 	), $atts )
	// );

	// Code
// $output = '<ul>';
$query_posts = new Query_Posts( $atts );
$output = $query_posts->output();
// $output = '</ul>';
return $output;

}
add_shortcode( 'query_posts', 'query_posts_shortcode' );
