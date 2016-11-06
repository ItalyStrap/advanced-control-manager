<?php
/**
 * Widget Areas API: Widget Areas class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widget\Areas;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

// use ItalyStrap\Core\Inline_Style;

/**
 * Widget Areas Class
 */
class Areas {

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
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options = array() ) {
		// $this->sidebars = $options;
		$this->sidebars = get_option( 'italystrap_widget_area' );
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_style( $style = '' ) {

		$css = '.' . esc_attr( $style['id'] ) . ' > div{';

		foreach ( $style['style'] as $key => $value ) {
			if ( '#' === $value ) {
				$value = 'transparent';
			}
			$css .= esc_attr( $key ) . ':' . esc_attr( $value ) . ';';
		}

		$css .= '}';
	
		return $css;
	
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
			\ItalyStrap\Core\get_attr( $sidebar_id, array( 'class' => 'widget_area ' . $sidebar_id, 'id' => $sidebar_id ), false ),
			\ItalyStrap\Core\get_attr( $sidebar_id . '_container', array( 'class' => $container_width ), false ),
			\ItalyStrap\Core\get_attr( $sidebar_id . '_row', array( 'class' => 'row' ), false ),
			dynamic_sidebar( $sidebar_id )

		);

		return $output;
	
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function add_widget_area( $id ) {

		$sidebar_id = $this->sidebars[ $id ]['value']['id'];
		$container_width = $this->sidebars[ $id ]['container_width'];

		// $css =  $this->get_style( $this->sidebars[ $id ] );
		/**
		 * <style scoped><?php echo $css; ?></style>
		 */
		// Inline_Style::set( $css );

		if ( is_active_sidebar( $sidebar_id ) ) :
			// $this->get_widget_area( $sidebar_id, $container_width );
		?>
		<div <?php \ItalyStrap\Core\get_attr( $sidebar_id, array( 'class' => 'widget_area ' . $sidebar_id, 'id' => $sidebar_id ), true ) ?>>
			<div <?php \ItalyStrap\Core\get_attr( $sidebar_id . '_container', array( 'class' => $container_width ), true ) ?>>
				<div <?php \ItalyStrap\Core\get_attr( $sidebar_id . '_row', array( 'class' => 'row' ), true ) ?>>
					<?php dynamic_sidebar( $sidebar_id ); ?>
				</div>
			</div>
		</div>
		<?php
		endif;
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
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

			// d( $sidebar );
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

		// foreach ( $this->sidebars as $key => $value ) {
		// 	if ( in_array( $post->post_name, $value, true ) && ! $update ) {
		// 		return $post_ID;
		// 	}
		// }
// d( in_array( $post->post_name, $this->sidebars, true ) );
// die();
		// if ( in_array( $post->post_name, $this->sidebars, true ) ) {
		// 	return $post_ID;
		// }

		// $array = array(
		// 	$_POST['post_ID'],
		// 	$_POST['post_title'],
		// 	$_POST['excerpt'],
		// 	$_POST['post_name'],
		// 	$_POST['_italystrap_action'],
		// 	$_POST['_italystrap_before_widget'],
		// 	$_POST['_italystrap_after_widget'],
		// 	$_POST['_italystrap_before_title'],
		// 	$_POST['_italystrap_after_title'],
		// );
		// 
		// $array2 = array(
		// 	'footer-box-1' => array(
		// 		'id'		=> $post->post_name,
		// 		'action'	=> '',
		// 		'priotity'	=> 10,
		// 		'value'		=> array(
		// 			'name'				=> $post->post_title,
		// 			'id'				=> $post->post_name,
		// 			'description'		=> $post->post_excerpt,
		// 			'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
		// 			'after_widget' 		=> '</div>',
		// 			'before_title'		=> '<h3 class="widget-title">',
		// 			'after_title'		=> '</h3>',
		// 		),
		// 	),
		// );
		// 
		// delete_option( 'italystrap_widget_area' );




		// $action = get_post_meta( $post_ID, '_italystrap_action', true );
		// $background_color = get_post_meta( $post_ID, '_italystrap_background_color', true );
		// $container_width = get_post_meta( $post_ID, '_italystrap_container_width', true );

		// if ( ! $action ) {
			$action = isset( $_POST['_italystrap_action'] ) ? wp_unslash( $_POST['_italystrap_action'] ) : '';
		// }

		// if ( ! $background_color ) {
			$background_color = isset( $_POST['_italystrap_background_color'] ) ? wp_unslash( $_POST['_italystrap_background_color'] ) : '';
		// }

		// if ( ! $container_width ) {
			$container_width = isset( $_POST['_italystrap_container_width'] ) ? wp_unslash( $_POST['_italystrap_container_width'] ) : '';
		// }

		// if ( ! $priority ) {
			$priority = isset( $_POST['_italystrap_priority'] ) ? absint( $_POST['_italystrap_priority'] ) : 10;
		// }


		$this->sidebars[ $post_ID ] = array(
			'id'				=> $post->post_name,
			'action'			=> $action,
			'priotity'			=> $priority,
			'style'				=> array(
				'background-color'	=> $background_color,
				),
			'container_width'	=> $container_width,
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
		if ( ! current_user_can( 'edit_theme_options' ) ) return;

		$page = 'themes.php';

		$singular = __( 'Widget Area', 'woosidebars' );
		$plural = __( 'Widget Areas', 'woosidebars' );
		$rewrite = array( 'slug' => 'sidebars' );
		$supports = array( 'title', 'excerpt' );

		if ( $rewrite == '' ) { $rewrite = 'sidebar'; }

		$labels = array(
			'name' => _x( 'Widget Areas', 'post type general name', 'woosidebars' ),
			'singular_name' => _x( 'Widget Area', 'post type singular name', 'woosidebars' ),
			'add_new' => _x( 'Add New', 'Widget Area' ),
			'add_new_item' => sprintf( __( 'Add New %s', 'woosidebars' ), $singular ),
			'edit_item' => sprintf( __( 'Edit %s', 'woosidebars' ), $singular ),
			'new_item' => sprintf( __( 'New %s', 'woosidebars' ), $singular ),
			'all_items' => sprintf( __( 'Widget Areas', 'woosidebars' ), $plural ),
			'view_item' => sprintf( __( 'View %s', 'woosidebars' ), $singular ),
			'search_items' => sprintf( __( 'Search %a', 'woosidebars' ), $plural ),
			'not_found' =>  sprintf( __( 'No %s Found', 'woosidebars' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'woosidebars' ), $plural ),
			'parent_item_colon' => '',
			'menu_name' => $plural

		);
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'show_in_menu' => $page,
			'query_var' => true,
			'rewrite' => $rewrite,
			'capability_type' => 'post',
			'has_archive' => 'sidebars',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $supports
		);
		register_post_type( 'sidebar', $args );
	} // End register_post_type()
}
