<?php
/**
 * Widget API: Widget logic class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widget;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * Widget Logic
 */
class Widget_Logic_Admin {

	/**
	 * Plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Field object
	 *
	 * @var Fields
	 */
	private $fields_type;

	/**
	 * [__construct description]
	 *
	 * @param array $options Plugin options.
	 */
	function __construct( array $options = array(), \ItalyStrap\Admin\Fields $fields_type ) {

		$this->fields_type = $fields_type;

		$this->options = $options;

		$this->wl_load_points = array(
			'plugins_loaded'    => __( 'when plugin starts (default)', 'italystrap' ),
			'after_setup_theme' => __( 'after theme loads', 'italystrap' ),
			'wp_loaded'         => __( 'when all PHP loaded', 'italystrap' ),
			'wp_head'           => __( 'during page header', 'italystrap' ),
		);

		if ( ( ! $this->options = get_option( 'widget_logic' )) || ! is_array( $this->options ) ) {
			$this->options = array();
		}
	}

	/**
	 * wp-admin/widgets.php explicitly checks current_user_can('edit_theme_options')
	 * which is enough security, I believe. If you think otherwise please contact me
	 * CALLED VIA 'widget_update_callback' FILTER (ajax update of a widget)
	 *
	 * @link https://developer.wordpress.org/reference/hooks/widget_update_callback/
	 *
	 * @param  array     $instance     The current widget instance's settings.
	 * @param  array     $new_instance Array of new widget settings.
	 * @param  array     $old_instance Array of old widget settings.
	 * @param  WP_Widget $wp_widget    The current widget instance.
	 *
	 * @return array                   The current widget instance's settings.
	 */
	function widget_update_callback( array $instance, array $new_instance, array $old_instance, \WP_Widget $wp_widget ) {

		if ( ! isset( $_POST[ $wp_widget->id . '-widget_logic' ] ) ) {
			return $instance;
		}

		$this->options[ $wp_widget->id ] = trim( $_POST[ $wp_widget->id . '-widget_logic' ] );
		$this->update_option();

		return $instance;
	}


	/**
	 * CALLED VIA 'sidebar_admin_setup' ACTION
	 * adds in the admin control per widget, but also processes import/export
	 */
	function expand_control() {

		global $wp_registered_widgets, $wp_registered_widget_controls;

		// EXPORT ALL OPTIONS
		$this->export_options();

		// IMPORT ALL OPTIONS
		$this->import_options();

		/**
		 * ADD EXTRA WIDGET LOGIC FIELD TO EACH WIDGET CONTROL
		 * pop the widget id on the params array (as it's not in the main params so not provided to the callback)
		 */
		foreach ( (array) $wp_registered_widgets as $id => $widget ) {

			/**
			 * Controll-less widgets need an empty function
			 * so the callback function is called.
			 */
			if ( ! isset( $wp_registered_widget_controls[ $id ] ) ) {
				wp_register_widget_control( $id, $widget['name'], array( $this, 'widget_logic_empty_control' ) );
			}

			$wp_registered_widget_controls[ $id ]['callback_wl_redirect'] = $wp_registered_widget_controls[ $id ]['callback'];

			$wp_registered_widget_controls[ $id ]['callback'] = array( $this, 'widget_logic_extra_control' );

			array_push( $wp_registered_widget_controls[ $id ]['params'], $id );
		}

		/**
		 * UPDATE WIDGET LOGIC WIDGET OPTIONS (via accessibility mode?)
		 */
		if ( 'post' === strtolower( $_SERVER['REQUEST_METHOD'] ) ) {

			foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id ) {

				if ( isset( $_POST[ $widget_id . '-widget_logic' ] ) ) {

					$this->options[ $widget_id ] = trim( $_POST[ $widget_id . '-widget_logic' ] );
				}
			}

			/**
			 * clean up empty options (in PHP5 use array_intersect_key)
			 *
			 * @var array
			 */
			$regd_plus_new = array_merge(
				array_keys( $wp_registered_widgets ),
				array_values( (array) $_POST['widget-id'] ),
				array(
					'widget_logic-options-filter',
					'widget_logic-options-wp_reset_query',
					'widget_logic-options-load_point'
					)
				);

			foreach ( array_keys( $this->options ) as $key ) {

				if ( ! in_array( $key, $regd_plus_new, true ) ) {
					unset( $this->options[ $key ] );
				}
			}
		}

		/**
		 * UPDATE OTHER WIDGET LOGIC OPTIONS
		 * must update this to use http://codex.wordpress.org/Settings_API
		 */
		if ( isset( $_POST['widget_logic-options-submit'] ) ) {

			$this->options['widget_logic-options-filter'] = $_POST['widget_logic-options-filter'];

			$this->options['widget_logic-options-wp_reset_query'] = $_POST['widget_logic-options-wp_reset_query'];

			$this->options['widget_logic-options-load_point'] = $_POST['widget_logic-options-load_point'];
		}

		$this->update_option();

	}

	/**
	 * CALLED VIA 'sidebar_admin_page' ACTION
	 * output extra HTML
	 * To update using http://codex.wordpress.org/Settings_API asap.
	 */
	function options_control() {

		global $wp_registered_widget_controls;

		if ( isset( $this->options['msg'] ) ) {

			if ( 'OK' === substr( $this->options['msg'], 0, 2 ) ) {

				echo '<div id="message" class="updated">';
			} else {

				echo '<div id="message" class="error">';
			}

			echo '<p>Widget Logic – ' . $this->options['msg'] . '</p></div>';

			unset( $this->options['msg'] );

			$this->update_option();
		}

		/**
		 * Get the view file
		 * view-widget-admin.php
		 */
		include( ITALYSTRAP_PLUGIN_PATH . '/widget/view/view-widget-admin.php' );

	}

	/**
	 * Added to widget functionality in 'widget_logic_expand_control' (above).
	 */
	function widget_logic_empty_control() {}



	/**
	 * Added to widget functionality in 'widget_logic_expand_control' (above).
	 */
	function widget_logic_extra_control() {

		global $wp_registered_widget_controls;

		$params = func_get_args();
		$id = array_pop( $params );

		/**
		 * Go to the original control function.
		 *
		 * @var string
		 */
		$callback = $wp_registered_widget_controls[ $id ]['callback_wl_redirect'];

		if ( is_callable( $callback ) ) {
			call_user_func_array( $callback, $params );
		}

		$value = ! empty( $this->options[ $id ] ) ? htmlspecialchars( stripslashes( $this->options[ $id ] ), ENT_QUOTES ) : '';

		// Dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface.
		$id_disp = $id;
		if ( ! empty( $params ) && isset( $params[0]['number'] ) ) {

			$number = $params[0]['number'];

			if ( -1 === $number ) {

				$number = '__i__';
				$value = '';
			}

			$id_disp = $wp_registered_widget_controls[ $id ]['id_base'] . '-' . $number;
		}

		$key = array(
			'name'		=> __( 'Widget logic', 'italystrap' ),
			'desc'		=> __( 'Add conditional tag to this widget.', 'ItalyStrap' ),
			'id'		=> $id_disp . '-widget_logic',
			'type'		=> 'textarea',
			'class'		=> 'widefat widget_logic',
			'default'	=> '',
		);

		$key['_id'] = $key['id'];
		$key['_name'] = $key['id'];

		echo '<p>' . $this->fields_type->field_type_textarea( $key ) . '</p>';
	}

	/**
	 * Export options
	 */
	public function export_options() {
	
		if ( isset( $_GET['wl-options-export'] ) ) {
			header( 'Content-Disposition: attachment; filename=widget_logic_options.txt' );
			header( 'Content-Type: text/plain; charset=utf-8' );

			echo "[START=WIDGET LOGIC OPTIONS]\n";
			foreach ( $this->options as $id => $text ) {
				echo "$id\t" . json_encode( $text ) . "\n";
			}
			echo '[STOP=WIDGET LOGIC OPTIONS]';
			exit;
		}

	}

	/**
	 * Import options
	 */
	public function import_options() {
	
		// IMPORT ALL OPTIONS.
		if ( isset( $_POST['wl-options-import'] ) ) {

			if ( $_FILES['wl-options-import-file']['tmp_name'] ) {

				$import = split( "\n", file_get_contents( $_FILES['wl-options-import-file']['tmp_name'], false ) );

				if ( '[START=WIDGET LOGIC OPTIONS]' === array_shift( $import ) && '[STOP=WIDGET LOGIC OPTIONS]' === array_pop( $import ) ) {

					foreach ( $import as $import_option ) {

						list( $key, $value ) = split( "\t", $import_option );
						$this->options[ $key ] = json_decode( $value );
					}

					$this->options['msg'] = __( 'Success! Options file imported','italystrap' );

				} else {
					$this->options['msg'] = __( 'Invalid options file','italystrap' );
				}
			} else {
				$this->options['msg'] = __( 'No options file provided','italystrap' );
			}

			$this->update_option();
			wp_redirect( admin_url( 'widgets.php' ) );
			exit;
		}

	}

	/**
	 * Update options.
	 */
	public function update_option() {

		update_option( 'widget_logic', $this->options );

	}
}
