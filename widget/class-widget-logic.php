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
class Widget_Logic {

	/**
	 * Plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * [__construct description]
	 *
	 * @param array $options Plugin options.
	 */
	function __construct( array $options = array() ) {

		$this->options = $options;

		$wl_load_points = array(
			'plugins_loaded'    => __( 'when plugin starts (default)', 'widget-logic' ),
			'after_setup_theme' => __( 'after theme loads', 'widget-logic' ),
			'wp_loaded'         => __( 'when all PHP loaded', 'widget-logic' ),
			'wp_head'           => __( 'during page header', 'widget-logic' ),
		);

		if ( ( ! $this->options = get_option( 'widget_logic' )) || ! is_array( $this->options ) ) {
			$this->options = array();
		}

		if (
			isset( $this->options['widget_logic-options-load_point'] )
			&& ( 'plugins_loaded' !== $this->options['widget_logic-options-load_point'] )
			&& array_key_exists( $this->options['widget_logic-options-load_point'],$wl_load_points )
			) {
			add_action( $this->options['widget_logic-options-load_point'], array( $this, 'widget_logic_sidebars_widgets_filter_add' ) );
		} else {
			$this->widget_logic_sidebars_widgets_filter_add();
		}

		if ( isset( $this->options['widget_logic-options-filter'] ) && 'checked' === $this->options['widget_logic-options-filter'] ) {

			/**
			 * Redirect the widget callback so the output can be buffered and filtered.
			 */
			add_filter( 'dynamic_sidebar_params', array( $this, 'widget_logic_widget_display_callback' ), 10 );
		}
	}

	/**
	 * [widget_logic_sidebars_widgets_filter_add description].
	 */
	function widget_logic_sidebars_widgets_filter_add() {

		/**
		 * Actually remove the widgets from the front end depending on widget logic provided.
		 */
		add_filter( 'sidebars_widgets', array( $this, 'widget_logic_filter_sidebars_widgets' ), 10 );
	}

	/**
	 * // FRONT END FUNCTIONS...
	 * // CALLED ON 'sidebars_widgets' FILTER
	 *
	 * @param  [type] $sidebars_widgets [description].
	 *
	 * @return [type]                   [description]
	 */
	function widget_logic_filter_sidebars_widgets( $sidebars_widgets ) {
		global $wp_reset_query_is_done;

		/**
		 * Reset any database queries done now that we're about to make decisions based on the context given in the WP query for the page.
		 */
		if ( ! empty( $this->options['widget_logic-options-wp_reset_query'] ) && ( 'checked' === $this->options['widget_logic-options-wp_reset_query'] ) && empty( $wp_reset_query_is_done ) ) {
			wp_reset_postdata();
			$wp_reset_query_is_done = true;
		}

		/**
		 * Loop through every widget in every sidebar (barring 'wp_inactive_widgets') checking WL for each one
		 *
		 * @var [type]
		 */
		foreach ( $sidebars_widgets as $widget_area => $widget_list ) {

			if ( 'wp_inactive_widgets' === $widget_area || empty( $widget_list ) ) {
				continue;
			}

			foreach ( $widget_list as $pos => $widget_id ) {

				if ( empty( $this->options[ $widget_id ] ) ) {
					continue;
				}

				$wl_value = stripslashes( trim( $this->options[ $widget_id ] ) );

				if ( empty( $wl_value ) ) {
					continue;
				}

				$wl_value = apply_filters( 'widget_logic_eval_override', $wl_value );

				if ( false === $wl_value ) {
					unset( $sidebars_widgets[ $widget_area ][ $pos ] );
					continue;
				}

				if ( true === $wl_value ) {
					continue;
				}

				if ( stristr( $wl_value, 'return' ) === false ) {
					$wl_value = 'return (' . $wl_value . ');';
				}

				if ( ! eval( $wl_value ) ) {
					unset( $sidebars_widgets[ $widget_area ][ $pos ] );
				}
			}
		}
		return $sidebars_widgets;
	}

	/**
	 * If 'widget_logic-options-filter' is selected the widget_content filter is implemented...
	 * CALLED ON 'dynamic_sidebar_params' FILTER - this is called during 'dynamic_sidebar' just before each callback is run
	 * swap out the original call back and replace it with our own
	 *
	 * @param  [type] $params [description].
	 *
	 * @return [type]         [description]
	 */
	function widget_logic_widget_display_callback( $params ) {

		global $wp_registered_widgets;
		$id = $params[0]['widget_id'];
		$wp_registered_widgets[ $id ]['callback_wl_redirect'] = $wp_registered_widgets[ $id ]['callback'];
		$wp_registered_widgets[ $id ]['callback'] = array( $this, 'widget_logic_redirected_callback' );

		return $params;
	}


	/**
	 * The redirection comes here
	 */
	function widget_logic_redirected_callback() {
		global $wp_registered_widgets, $wp_reset_query_is_done;

		/**
		 * Replace the original callback data
		 *
		 * @var [type]
		 */
		$params = func_get_args();
		$id = $params[0]['widget_id'];
		$callback = $wp_registered_widgets[ $id ]['callback_wl_redirect'];
		$wp_registered_widgets[ $id ]['callback'] = $callback;

		/**
		 * Run the callback but capture and filter
		 * the output using PHP output buffering
		 */
		if ( is_callable( $callback ) ) {
			ob_start();
			call_user_func_array( $callback, $params );
			$widget_content = ob_get_contents();
			ob_end_clean();
			echo apply_filters( 'widget_content', $widget_content, $id ); // XSS ok.
		}
	}
}
