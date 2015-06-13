<?php
/**
 *	Plugin Name:	ItalyStrap
 *	Plugin URI:		http://www.italystrap.it
 *	Description:	Make your website more powerfull. | <a href="admin.php?page=italystrap-documentation">Documentation</a> 
 *	Version:		1.3.1
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

/**
 * This will make shure the plugin files can't be accessed within the web browser directly.
 */
if ( !defined( 'WPINC' ) )
	die;

/**
 * Define some costant for internal use
 */
if ( !defined( 'ITALYSTRAP_PLUGIN' ) )
	define('ITALYSTRAP_PLUGIN', true);

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended\italystrap.php
 */
if ( !defined( 'ITALYSTRAP_FILE' ) )
	define('ITALYSTRAP_FILE', __FILE__ );

/**
 * Example = F:\xampp\htdocs\italystrap\wp-content\plugins\italystrap-extended/
 */
if ( !defined( 'ITALYSTRAP_PLUGIN_PATH' ) )
	define('ITALYSTRAP_PLUGIN_PATH', plugin_dir_path( ITALYSTRAP_FILE ));
/**
 * Example = italystrap-extended/italystrap.php
 */
if ( !defined( 'ITALYSTRAP_BASENAME' ) )
	define('ITALYSTRAP_BASENAME', plugin_basename( ITALYSTRAP_FILE ));

/**
 * Require PHP autoload
 */
require(ITALYSTRAP_PLUGIN_PATH . 'vendor/autoload.php');

/**
 * Require Debug file
 */
require(ITALYSTRAP_PLUGIN_PATH . 'debug/debug.php');

/**
 * Initialize class
 */

if ( ! class_exists( 'ItalyStrapInit' ) ) {

	class ItalyStrapInit{

		private $options = '';
		
		public function __construct(){

			$this->options = get_option( 'italystrap_settings' );

			/**
			 * Istantiate this class only if is admin
			 */
			if ( is_admin() ) {

				new ItalyStrapAdmin;
				new ItalyStrapAdminMediaSettings;
				new ItalyStrapAdminGallerySettings;
			}			

			/**
			 * adjust priority to make sure this runs
			 */
			add_action( 'init', array( $this, 'italystrap_init'), 100 );

			/**
			 * 
			 */
			add_action( 'wp_head', array( $this, 'italystrap_print_inline_css_in_header'), 999 );

			/**
			 * Print inline script in footer
			 * Load after all and before shotdown hook
			 */
			add_action( 'wp_print_footer_scripts', array( $this, 'italystrap_print_inline_script_in_footer'), 999 );

			if ( isset( $this->options['lazyload'] ) && !is_admin() )
				ItalyStrapLazyload::init();

			if ( isset( $this->options['vcardwidget'] ) )
			add_action('widgets_init', function(){ register_widget('ItalyStrapVcardWidget' ); } );
			
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

			if( isset($post->post_content) && has_shortcode( $post->post_content, 'gallery') )
			        $gallery = true; // http://dannyvankooten.com/3935/only-load-contact-form-7-scripts-when-needed/

			if( !$gallery )
			    new ItalyStrapCarouselLoader();

		}

		/**
		 * Print inline script in footer after all and before shotdown hook
		 * @return string Internal script
		 * @todo Creare un sistema che appenda regolarmente dopo gli script
		 *       e tenga presente delle dipendenze da jquery
		 */
		public function italystrap_print_inline_script_in_footer(){

			$scipt = ItalyStrapGlobals::get();

			if ($scipt) echo '<script type="text/javascript">/*<![CDATA[*/' . $scipt . '/*]]>*/</script>';

			else echo '';

		}

		public function italystrap_print_inline_css_in_header(){

			$css = ItalyStrapGlobalsCss::get();

			if ($css) echo '<style>' . $css . '</style>';

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
$detect = new Mobile_Detect;