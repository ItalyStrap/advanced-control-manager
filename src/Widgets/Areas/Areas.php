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
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Update\Update;

/**
 * Widget Areas Class
 */
class Areas extends Areas_Base implements Subscriber_Interface {

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
			'save_post_sidebar'		=> array(
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
	 * Add widget area to the front-end
	 *
	 * @param  int    $id The numeric ID of the single sidebar registered.
	 */
	public function add_widget_area( $id ) {

		$sidebar_id = esc_attr( $this->get_the_id( $id ) );

		if ( ! is_active_sidebar( $sidebar_id ) ) {
			return;
		}

		/**
		 * Example:
		 * 	add_filter( 'italystrap_widget_area_visibility', function ( $bool, $sidebar_id ) {
		 * 		// Check for sidebar ID
		 *		if ( 'hero-bottom' !== $sidebar_id ) {
		 *			return $bool;
		 *		}
		 *	 	// Visibility rule
		 *		if ( is_page() ) {
		 *			return false;
		 *		}
		 *	 	// Always return the $bool variable
		 *		return $bool;
		 *	}, 10, 2 );
		 */
		if ( ! apply_filters( 'italystrap_widget_area_visibility', true, $sidebar_id ) ) {
			return;
		}

		foreach ( $this->default as $key => $value ) {
			if ( ! isset( $this->sidebars[ $id ][ $key ] ) ) {
				$this->sidebars[ $id ][ $key ] = $this->default[ $key ]['default'];
			}
		}

		$container_width = $this->sidebars[ $id ]['container_width'];
		$widget_area_class = $this->sidebars[ $id ]['widget_area_class'];

		$this->css->style( $this->sidebars[ $id ] );

		$widget_area_attr = array(
			'class' => 'widget_area ' . $sidebar_id . ' ' . esc_attr( $widget_area_class ),
			'id'	=> $sidebar_id,
		);

		require( __DIR__ . '/view/widget-area.php' );
	}

	/**
	 * Register the sidebars
	 */
	public function register_sidebars() {

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

		static $run_once = false;
		if ( $run_once ) {
			return $post_ID;
		}

		// error_log( print_r( $run_once, true ) );

		// delete_option( 'italystrap_widget_area' );

		$fields = $this->default;

		$fields['background_image']['id'] = $this->_prefix . '_background_image_id';

		$instance = array();

		$instance = $this->update->update( $_POST, $fields );

		// error_log( print_r( $instance, true ) );

		$this->sidebars[ $post_ID ] = array(
			'id'				=> $post->post_name,
			'action'			=> $instance[ $this->_prefix . '_action' ],
			'priotity'			=> (int) $instance[ $this->_prefix . '_priority' ],
			'style'				=> array(
				'background-color'	=> $instance[ $this->_prefix . '_background_color' ],
				'background-image'	=> (int) $instance[ $this->_prefix . '_background_image_id'],
			),
			'widget_area_class'	=> $instance[ $this->_prefix . '_widget_area_class' ],
			'container_width'	=> $instance[ $this->_prefix . '_container_width' ],
			'value'				=> array(
				'name'				=> $post->post_title,
				'id'				=> $post->post_name,
				'description'		=> $post->post_excerpt,
				'before_widget'		=> sprintf(
					'<%s %s>',
					$instance[ $this->_prefix . '_widget_before_after' ],
					\ItalyStrap\Core\get_attr( $post->post_name, array( 'class' => 'widget %2$s', 'id' => '%1$s' ) )
				),
				'after_widget' 		=> sprintf(
					'</%s>',
					$instance[ $this->_prefix . '_widget_before_after' ]
				),

				'before_title'		=> sprintf(
					'<%s class="widget-title %s">',
					$instance[ $this->_prefix . '_widget_title_before_after' ],
					$instance[ $this->_prefix . '_widget_title_class' ]
				),
				'after_title'		=> sprintf(
					'</%s>',
					$instance[ $this->_prefix . '_widget_title_before_after' ]
				),
			),
		);

		// error_log( print_r( $this->sidebars, true ) );
		// $this->reorder_sidebar( $post_ID, $instance, '', $post );

		update_option( 'italystrap_widget_area', $this->sidebars );

		$run_once = true;

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
	 * Reorder Widget Areas
	 *
	 * @param array|object $postarr  Optional. Post data. Arrays are expected to be escaped,
	 *                               objects are not. Default array.
	 */
	public function reorder_sidebar( $post_id = null, $instance = '', $postarr = '', $post = null ) {

		// Reorder by priority
		// And reorder by action order

		// error_log( print_r( get_post_meta( $post_id, $this->_prefix . '_action', true ), true ) );

		// var_dump( get_post_meta( $post_id, $this->_prefix . '_action', true ) );

		// global $wp_filter;

		// error_log( print_r( $wp_filter[ $instance[ $this->_prefix . '_action' ] ], true ) );
		// $this->sidebars[ $post_id ]['priotity']

		$temp = array();
		
		// foreach ( $this->sidebars as $key => $value ) {
		// 	$temp[ $this->sidebars[ $key ]['action'] ] = $this->sidebars[ $key ]['priotity'];
		// }


		foreach ( apply_filters( 'italystrap_widget_area_position', array() ) as $key => $value ) {
			foreach ( $this->sidebars as $id => $config ) {
				if ( $config['action'] === $key ) {
					$temp[ $id ] = $config;
				}
			}
		}

		// error_log( print_r( $temp, true ) );
		// error_log( print_r( $post, true ) );
		// error_log( print_r( apply_filters( 'italystrap_widget_area_position', array() ), true ) );
	}
}
