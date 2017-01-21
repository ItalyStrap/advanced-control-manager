<?php
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Core;

use ItalyStrap\Core\Asset\Inline_Style;
use ItalyStrap\Core\Asset\Inline_Script;

use ItalyStrap\Shortcode\Gallery;

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
			$shortcode_carousel = new Gallery();
			add_filter( 'post_gallery', array( $shortcode_carousel, 'gallery_shortcode' ), 10, 3 );
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
			'media_carousel_widget'	=> 'Carousel',
			'widget_posts'			=> 'Posts',
			'widget_vcard'			=> 'VCard', // New
			'widget_image'			=> 'Image', // New
			'widget_facebook_page'	=> 'Facebook_Page', // New
		);

		foreach ( (array) $widget_list as $key => $value ) {
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

		$script = apply_filters( 'italystrap_custom_inline_script', Inline_Script::get() );

		if ( ! $script ) {
			return;
		}

		printf(
			'<script type="text/javascript">/*<![CDATA[*/%s/*]]>*/</script>',
			$script
		);

	}

	/**
	 * Print inline css.
	 */
	public function print_inline_css_in_header() {

		$css = apply_filters( 'italystrap_custom_inline_style', Inline_Style::get() );

		if ( empty( $css ) ) {
			return;
		}

		printf(
			'<style type="text/css" id="custom-inline-css">%s</style>',
			wp_strip_all_tags( $css )
		);
	}
} // End Init

if ( ! empty( $options['widget_areas'] ) ) {
	/**
	 * Instantiate Widget Areas Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var Areas
	 */
	$widget_areas = $injector->make( 'ItalyStrap\Widget\Areas\Areas' );
	$widget_areas->register_sidebars();
	add_action( 'init', array( $widget_areas, 'register_post_type' ), 20 );
	add_action( 'save_post', array( $widget_areas, 'add_sidebar' ), 10, 3 );
	add_action( 'edit_post', array( $widget_areas, 'add_sidebar' ), 10, 2 );
	add_action( 'delete_post', array( $widget_areas, 'delete_sidebar' ) );
	add_action( 'widgets_admin_page', array( $widget_areas, 'print_add_button' ) );

	// delete_option( 'italystrap_widget_area' );
	// d( get_option( 'italystrap_widget_area' ) );
}

if ( ! empty( $options['widget_visibility'] ) ) {
	add_action( 'init', array( 'ItalyStrap\Widget\Visibility\Visibility', 'init' ) );
}

// if ( ! empty( $options['shortcode_widget'] ) ) {
	/**
	 * Instantiate Widget to Shortcode Class
	 * Questa deve essere eseguita sia in admin che in front-end
	 *
	 * @var ItalyStrap\Shortcode\Widget
	 */
	// $shortcode_widget = $injector->make( 'ItalyStrap\Shortcode\Widget' );

	// add_shortcode( 'widget', array( $shortcode_widget, 'shortcode' ) );

	// add_action( 'widgets_init', array( $shortcode_widget, 'arbitrary_sidebar' ), 20 );
	// add_action( 'in_widget_form', array( $shortcode_widget, 'in_widget_form' ), 10, 3 );
	// add_filter( 'mce_external_plugins', array( $shortcode_widget, 'mce_external_plugins' ) );
	// add_filter( 'mce_buttons', array( $shortcode_widget, 'mce_buttons' ) );
	// add_action( 'admin_enqueue_scripts', array( $shortcode_widget, 'editor_parameters' ) );
	// add_action( 'wp_enqueue_scripts', array( $shortcode_widget, 'editor_parameters' ) );
// }

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
	 * WooCommerce enqueue style
	 *
	 * @link https://docs.woothemes.com/document/css-structure/
	 */
	function dequeue_unused_styles( $enqueue_styles ) {

		if ( is_cart() || is_checkout() || is_account_page() ) {

			return $enqueue_styles;

		} else {

			return false;

		}


	}
	// add_filter( 'woocommerce_enqueue_styles', __NAMESPACE__ . '\dequeue_unused_styles' );

	// Or just remove them all in one line.
	// add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	// 
	$search_box_item = new Search_Box_Item();


	/**
	 * @link http://www.wpbeginner.com/plugins/add-excerpts-to-your-pages-in-wordpress/
	 * @link https://codex.wordpress.org/Function_Reference/add_post_type_support
	 */
	// add_action( 'init', __NAMESPACE__ .'\my_add_excerpts_to_pages' );
	// function my_add_excerpts_to_pages() {
	// 	add_post_type_support( 'page', 'excerpt' );
	// }


	/*************
	 * ADMIN INIT
	 ************/

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

	if ( isset( $options['category_posts_shortcode'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	if ( isset( $options['category_posts_widget'] ) ) {
		$shortcode_docs = new Shortcode_Docs;
		add_shortcode( 'docs', array( $shortcode_docs, 'docs' ) );
	}

	// $facebook_page = new \ItalyStrap\Core\Facebook\Page();
	// add_action( 'wp_footer', array( $facebook_page, 'script_2' ), 99 );
	// add_action( 'italystrap_sidebar', array( $facebook_page, 'output' ) );

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
