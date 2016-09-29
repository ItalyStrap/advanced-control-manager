<?php
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

use \ItalyStrap\Core\Asset\Inline_Style;
use \ItalyStrap\Core\Asset\Inline_Script;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Define HOME_URL
 */
if ( ! defined( 'HOME_URL' ) ) {
	define( 'HOME_URL', get_home_url( null, '/' ) );
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
		load_plugin_textdomain( 'italystrap', false, dirname( ITALYSTRAP_BASENAME ) . '/lang' );
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
			'widget_posts'			=> 'Posts',
			'media_carousel_widget'	=> 'Carousel',
			'widget_vcard'			=> 'VCard', // New
			'widget_image'			=> 'Image', // New
		);

		foreach ( $widget_list as $key => $value ) {
			if ( ! empty( $this->options[ $key ] ) ) {
				\register_widget( 'ItalyStrap\\Widget\\' . $value );
			}
		}

		if ( defined( 'ITALYSTRAP_BETA' ) ) {

			\register_widget( 'ItalyStrap\Widget\Breadcrumbs' );

			// if ( isset( $this->options['widget_product'] ) ) {
				// $widget_product = new Widget_Product;
				\register_widget( 'ItalyStrap\Widget\Products' );
				// register_widget( $widget_product );
			// }

			\register_widget( 'ItalyStrap\Widget\Taxonomies_Posts' );

		}

	}

	/**
	 * Print inline script in footer after all and before shotdown hook.
	 *
	 * @todo Creare un sistema che appenda regolarmente dopo gli script
	 *       e tenga presente delle dipendenze da jquery
	 */
	public function print_inline_script_in_footer() {

		$script = Inline_Script::get();

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

		$css = Inline_Style::get();

		if ( $css ) {
			echo '<style>' . wp_strip_all_tags( $css ) . '</style>';
		} else {
			echo '';
		}
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

	/*************
	 * GLOBAL INIT
	 ************/

	/**
	 * Instantiate Customizer_Manager Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Customizer_Manager
	 */
	$widget_areas = $injector->make( 'ItalyStrap\Widget\Areas\Areas' );
	$widget_areas->register_sidebars();
	add_action( 'init', array( $widget_areas, 'register_post_type' ), 20 );
	add_action( 'save_post', array( $widget_areas, 'add_sidebar' ), 10, 3 );
	add_action( 'edit_post', array( $widget_areas, 'add_sidebar' ), 10, 2 );
	add_action( 'delete_post', array( $widget_areas, 'delete_sidebar' ) );
	// delete_option( 'italystrap_widget_area' );
	// d( get_option( 'italystrap_widget_area' ) );

	/*************
	 * ADMIN INIT
	 ************/

	/**
	 * The array with all plugin options
	 */
	$options_arr[] = $options;
	$options_arr[] = array();
	$injector->defineParam( 'options_arr', $options_arr );

	$imp_exp_args = array(
		'name_action'	=> 'italystrap_action',
		'export_nonce'	=> 'italystrap_export_nonce',
		'import_nonce'	=> 'italystrap_import_nonce',
		'filename'		=> 'italystrap-plugin-settings-export-',
		'import_file'	=> 'italystrap_import_file',
		);
	$injector->defineParam( 'imp_exp_args', $imp_exp_args );

	/**
	 * Import Export functionality
	 *
	 * @var Import_Export
	 */
	$import_export = $injector->make( 'ItalyStrap\Admin\Import_Export' );
	add_action( 'admin_init', array( $import_export, 'export' ) );
	add_action( 'admin_init', array( $import_export, 'import' ) );

	/**
	 * Widget Logic Functionality for admin
	 *
	 * @var Widget_Logic_Admin
	 */
	// $widget_logic_admin = $injector->make( 'ItalyStrap\Widget\Widget_Logic_Admin' );

	/**
	 * Widget changes submitted by ajax method.
	 */
	// add_filter( 'widget_update_callback', array( $widget_logic_admin, 'widget_update_callback' ), 10, 4 );
	/**
	 * Before any HTML output save widget changes and add controls to each widget on the widget admin page.
	 */
	// add_action( 'sidebar_admin_setup', array( $widget_logic_admin, 'expand_control' ) );
	/**
	 * Add Widget Logic specific options on the widget admin page.
	 */
	// add_action( 'sidebar_admin_page', array( $widget_logic_admin, 'options_control' ) );
	// 

	/**
	 * FRONTEND INIT
	 */
	
	/**
	 * [$generate_analytics description]
	 *
	 * @var Generate_Analytics
	 */
	$generate_analytics = $injector->make( 'ItalyStrap\Core\Generate_Analytics' );
	add_action( 'wp_footer', array( $generate_analytics, 'render_analytics' ), 99999 );

	if ( isset( $options['category_posts_shortcode'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	if ( isset( $options['category_posts_widget'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	// function add_to_top() {

	// 	global $injector;

	// 	// $category_posts = new Category_Posts;
	// 	$category_posts = $injector->make( 'ItalyStrap\Core\Category_Posts' );
	// 	echo $category_posts->render();

	// 	return null;
	
	// }
	// add_action( 'italystrap_before_content', __NAMESPACE__ . '\add_to_top' );


	/**
	 * Widget Logic Functionality for admin
	 *
	 * @var Widget_Logic_Admin
	 */
	// $widget_logic_admin = $injector->make( 'ItalyStrap\Widget\Widget_Logic' );

	/**
	 * Widget changes submitted by ajax method.
	 */
	// add_filter( 'widget_update_callback', array( $widget_logic_admin, 'widget_update_callback' ), 10, 4 );
	/**
	 * Before any HTML output save widget changes and add controls to each widget on the widget admin page.
	 */
	// add_action( 'sidebar_admin_setup', array( $widget_logic_admin, 'expand_control' ) );
	/**
	 * Add Widget Logic specific options on the widget admin page.
	 */
	// add_action( 'sidebar_admin_page', array( $widget_logic_admin, 'options_control' ) );

}
