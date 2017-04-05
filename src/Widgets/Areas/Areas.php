<?php
/**
 * Widget Areas API: Widget Areas class
 *
 * @forked from woosidebars
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widgets\Areas;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Core;
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Update\Update;

/**
 * Widget Areas Class
 */
class Areas implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked 'widgets_init' - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'				=> 'method_name',
			'widgets_init'			=> 'register_sidebars',
			'init'					=> array(
				'function_to_add'		=> 'register_post_type',
				'priority'				=> 20,
			),
			'save_post'				=> array(
				'function_to_add'		=> 'add_sidebar',
				'accepted_args'     	=> 3
			),
			'edit_post'				=> array(
				'function_to_add'		=> 'add_sidebar',
				'accepted_args'     	=> 2
			),
			'delete_post'			=> 'delete_sidebar',
			'widgets_admin_page'	=> 'print_add_button',
		);
	}

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $options = null;

	/**
	 * Array with widget areas
	 *
	 * @var array
	 */
	private $widget_areas = array();

	/**
	 * Update object for saving data to DB
	 *
	 * @var Update
	 */
	protected $update = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options = array(), Update $update ) {
		// $this->sidebars = $options;
		$this->sidebars = get_option( 'italystrap_widget_area' );
		// delete_option( 'italystrap_widget_area' );
		// d( get_option( 'italystrap_widget_area' ) );
		$this->update = $update;

		$this->prefix = 'italystrap';

		$this->_prefix = '_' . $this->prefix;

		$default = require( __DIR__ . DIRECTORY_SEPARATOR . 'config.php' );

		$this->default = $default['fields'];
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_style( array $style = array() ) {

		$css = '.' . esc_attr( $style['id'] ) . '{';

		foreach ( $style['style'] as $key => $value ) {

			if ( '' === $value ) {
				continue;
			}

			if ( '#' === $value ) {
				$value = 'transparent';
			}

			if ( is_numeric( $value ) ) {
				$value = sprintf(
					'url(%s)',
					wp_get_attachment_image_url( $value, '' ) // 4.4.0
				);
				$css .= 'background-size:cover;';
			}
			$css .= esc_attr( $key ) . ':' . esc_attr( $value ) . ';';
		}

		$css .= '}';
	
		return $css;
	}

	/**
	 * Do style
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function style( array $style = array() ) {

		printf(
			'<style scoped>%s</style>',
			$this->get_style( $style )
		);	
	}

	/**
	 * Get the widget area
	 *
	 * @param  int    $id The sidebar ID.
	 * @return string     The html output
	 */
	public function get_widget_area( $sidebar_id, $container_width ) {
	
		// $output = sprintf(
		// 	'<style scoped>%s</style>',
		// 	esc_attr( $this->get_style( $this->sidebars[ $sidebar_id ] ) )
		// 	);

		$output = sprintf(
			'<div %1$s><div %2$s><div %3$s>%4$s</div></div></div>',
			Core\get_attr( $sidebar_id, array( 'class' => 'widget_area ' . $sidebar_id, 'id' => $sidebar_id ), false ),
			Core\get_attr( $sidebar_id . '_container', array( 'class' => $container_width ), false ),
			Core\get_attr( $sidebar_id . '_row', array( 'class' => 'row' ), false ),
			dynamic_sidebar( $sidebar_id )

		);

		return $output;
	}

	/**
	 * Get Sidebar ID
	 *
	 * @param  int    $id The numeric ID of the single sidebar registered.
	 *
	 * @return string     The ID of the sidebar to load on front-end
	 */
	protected function get_the_id( $id ) {
		return $this->sidebars[ $id ]['value']['id'];
	}

	/**
	 * Add widget area to the front-end
	 *
	 * @param  int    $id The numeric ID of the single sidebar registered.
	 */
	public function add_widget_area( $id ) {

		$sidebar_id = esc_attr( $this->get_the_id( $id ) );

		if ( ! is_active_sidebar( $sidebar_id ) ) {
			return;
		}

		// foreach ( array( 'container_width', 'widget_area_class' ) as $value ) {
		// 	if ( ! isset( $this->sidebars[ $id ][ $value ] ) ) {
		// 		$this->sidebars[ $id ][ $value ] = $this->default[ $value ]['default'];
		// 	}
		// }

		foreach ( $this->default as $key => $value ) {
			if ( ! isset( $this->sidebars[ $id ][ $key ] ) ) {
				$this->sidebars[ $id ][ $key ] = $this->default[ $key ]['default'];
			}
		}

		$container_width = $this->sidebars[ $id ]['container_width'];
		$widget_area_class = $this->sidebars[ $id ]['widget_area_class'];

		// d( $this->get_style( $this->sidebars[ $id ] ) );
		// $css =  $this->get_style( $this->sidebars[ $id ] );
		// d( $css );
		/**
		 * <style scoped><?php echo $css; ?></style>
		 */
		// Inline_Style::set( $css );

		// if ( ! is_active_sidebar( $sidebar_id ) ) {
		// 	return;
		// }
		// $this->get_widget_area( $sidebar_id, $container_width );
		$this->style( $this->sidebars[ $id ] );

		$widget_area_attr = array(
			'class' => 'widget_area ' . $sidebar_id . ' ' . esc_attr( $widget_area_class ),
			'id'	=> $sidebar_id,
		);

		?><div <?php Core\get_attr( $sidebar_id . '_widget_area', $widget_area_attr, true ) ?>>
			<div <?php Core\get_attr( $sidebar_id . '_container', array( 'class' => $container_width ), true ) ?>>
				<div <?php Core\get_attr( $sidebar_id . '_row', array( 'class' => 'row' ), true ) ?>>
					<?php dynamic_sidebar( $sidebar_id ); ?>
				</div>
			</div>
		</div><?php
	}

	/**
	 * Register the sidebars
	 */
	public function register_sidebars() {

		// delete_option( 'italystrap_widget_area' );
		// d( get_option( 'italystrap_widget_area' ) );
		// if ( ! is_admin() ) {
			// d( get_option( 'italystrap_widget_area' ) );
		// }
		$areas_obj = $this;

		foreach ( (array) $this->sidebars as $sidebar_key => $sidebar ) {

			if ( ! isset( $sidebar['value']['id'] ) ) {
				continue;
			}

			if ( strpos( $sidebar['id'], '__trashed' ) ) {
				continue;
			}

			if ( '' === $sidebar['action'] ) {
				continue;
			}

			register_sidebar( $sidebar['value'] );

			add_action( $sidebar['action'], function() use ( $sidebar_key, $areas_obj ) {
				$areas_obj->add_widget_area( $sidebar_key );
			}, absint( $sidebar['priotity'] ) );
		}
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function add_sidebar( $post_ID, $post, $update = false ) {

		if ( 'sidebar' !== $post->post_type ) {
			return $post_ID;
		}

		if ( '' === $post->post_name ) {
			return $post_ID;
		}

		if ( ! current_user_can( 'edit_post', $post_ID ) ) {
			return $post_ID;
		}

		if ( false === get_option( 'italystrap_widget_area' ) ) {
			add_option( 'italystrap_widget_area', array() );
		}

		// delete_option( 'italystrap_widget_area' );

		$fields = $this->default;

		$fields['background_image']['id'] = $this->_prefix . '_background_image_id';

		$instance = array();

		$instance = $this->update->update( $_POST, $fields );

		// error_log( print_r( $instance, true ) );

		$this->sidebars[ $post_ID ] = array(
			'id'				=> $post->post_name,
			'action'			=> $instance[ $this->_prefix . '_action'],
			'priotity'			=> (int) $instance[ $this->_prefix . '_priority'],
			'style'				=> array(
				'background-color'	=> $instance[ $this->_prefix . '_background_color'],
				'background-image'	=> (int) $instance[ $this->_prefix . '_background_image_id'],
			),
			'widget_area_class'	=> $instance[ $this->_prefix . '_widget_area_class'],
			'container_width'	=> $instance[ $this->_prefix . '_container_width'],
			'value'				=> array(
				'name'				=> $post->post_title,
				'id'				=> $post->post_name,
				'description'		=> $post->post_excerpt,
				'before_widget'		=> '<div ' . \ItalyStrap\Core\get_attr( $post->post_name, array( 'class' => 'widget %2$s', 'id' => '%1$s' ) ) . '>',
				'after_widget' 		=> '</div>',
				'before_title'		=> '<h3 class="widget-title">',
				'after_title'		=> '</h3>',
			),
		);

		// error_log( print_r( $this->sidebars, true ) );

		update_option( 'italystrap_widget_area', $this->sidebars );

		return $post_ID;
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function delete_sidebar( $post_id ) {

		if ( ! isset( $this->sidebars[ $post_id ] ) ) {
			return $post_id;
		}
	
		unset( $this->sidebars[ $post_id ] );

		update_option( 'italystrap_widget_area', $this->sidebars );
	}

	/**
	 * register_post_type function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_post_type() {
		// Allow only users who can adjust the theme to view the WooSidebars admin.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$page = 'themes.php';

		$singular = __( 'Widget Area', 'italystrap' );
		$plural = __( 'Widget Areas', 'italystrap' );
		$rewrite = array( 'slug' => 'sidebars' );
		$supports = array( 'title', 'excerpt' );

		if ( '' === $rewrite ) {
			$rewrite = 'sidebar';
		}

		$labels = array(
			'name'					=> _x( $plural, 'post type general name', 'italystrap' ),
			'singular_name'			=> _x( $singular, 'post type singular name', 'italystrap' ),
			'add_new'				=> _x( 'Add New', $singular ),
			'add_new_item'			=> sprintf(
				__( 'Add New %s', 'italystrap' ),
				$singular
			),
			'edit_item'				=> sprintf(
				__( 'Edit %s', 'italystrap' ),
				$singular
			),
			'new_item'				=> sprintf(
				__( 'New %s', 'italystrap' ),
				$singular
			),
			'all_items'				=> $plural,
			'view_item'				=> sprintf(
				__( 'View %s', 'italystrap' ),
				$singular
			),
			'search_items'			=> sprintf(
				__( 'Search %a', 'italystrap' ),
				$plural
			),
			'not_found'				=> sprintf(
				__( 'No %s Found', 'italystrap' ),
				$plural
			),
			'not_found_in_trash'	=> sprintf(
				__( 'No %s Found In Trash', 'italystrap' ),
				$plural
			),
			'parent_item_colon'		=> '',
			'menu_name'				=> $plural

		);
		$args = array(
			'labels'				=> $labels,
			'public'				=> false,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_in_admin_bar'     => true,
			'show_in_menu'			=> $page,
			'show_in_rest'			=> false,
			'query_var'				=> true,
			'rewrite'				=> $rewrite,
			'capability_type'		=> 'post',
			'has_archive'			=> 'sidebars',
			'hierarchical'			=> false,
			'menu_position'			=> null,
			'supports'				=> $supports
		);
		register_post_type( 'sidebar', $args );
	} // End register_post_type()

	/**
	 * Print add button in widgets.php.
	 *
	 * @hooked 'widgets_admin_page' - 10
	 */
	public function print_add_button() {
	
		printf(
			'<div><a %s>%s</a></div>',
			Core\get_attr(
				'widget_add_sidebar',
				array(
					'href'	=> 'post-new.php?post_type=sidebar',
					'class'	=> 'button button-primary sidebar-chooser-add',
					'style'	=> 'margin:1em 0;',
				),
				false
			),
			__( 'Add new widgets area', 'italystrap' )
		);
	}
}
