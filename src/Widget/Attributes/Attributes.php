<?php
/**
 * Widget Attribute API: Widget Attributes class
 *
 * Forked from an idea of Dzikri Aziz http://kucrut.org/widget-attributes/
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

namespace ItalyStrap\Widget\Attributes;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Fields\Fields_Interface;

/**
 * Class for adding field attributes for widget
 */
class Attributes {

	/**
	 * Variable to store field object.
	 *
	 * @var null
	 */
	private $fields_type = null;

	/**
	 * Init the constructor
	 *
	 * @param array            $options     The plugin options.
	 * @param Fields_Interface $fields_type Field object.
	 */
	public function __construct( array $options = array(), Fields_Interface $fields_type ) {

		$this->fields_type = $fields_type;

		$this->config = require( ITALYSTRAP_PLUGIN_PATH . 'config/widget-attributes.php' );

		$this->new_input_fields = array( 'widget_css_id', 'widget_css_css' );

	}

	/**
	 * Initialize plugin
	 */
	public function setup() {
		if ( is_admin() ) {
			// Add necessary input on widget configuration form.
			add_action( 'in_widget_form', array( __CLASS__, 'input_fields' ), 10, 3 );

			// Save widget attributes.
			add_filter( 'widget_update_callback', array( __CLASS__, 'save_attributes' ), 10, 4 );
		} else {
			// Insert attributes into widget markup.
			add_filter( 'dynamic_sidebar_params', array( __CLASS__, 'insert_attributes' ) );
		}
	}

	/**
	 * Create the Fields
	 *
	 * @access protected
	 * @param  WP_Widget $widget   Widget Object.
	 * @param  array     $instance The instance of the widget.
	 * @param  array     $key      The key of field's array to create the HTML field.
	 * @param  string    $out      The HTML form output.
	 * @return string              Return the HTML Fields
	 */
	protected function field_type( $widget, $instance, array $key, $out = '' ) {

		/* Set Defaults */
		$key['default'] = isset( $key['default'] ) ? ( (string) $key['default'] ) : '';

		if ( isset( $instance[ $key['id'] ] ) ) {

			if ( is_array( $instance[ $key['id'] ] ) ) {
				$key['value'] = $instance[ $key['id'] ];

			} else {
				$key['value'] = empty( $instance[ $key['id'] ] ) ? '' : strip_tags( $instance[ $key['id'] ] );
			}
		}

		/* Set field id and name  */
		$key['_id'] = $widget->get_field_id( $key['id'] );
		$key['_name'] = $widget->get_field_name( $key['id'] );

		/* Set field type */
		if ( ! isset( $key['type'] ) ) {
			$key['type'] = 'text';
		}

		/* Prefix method */
		$field_method = 'field_type_' . str_replace( '-', '_', $key['type'] );

		/* Check for <p> Class */
		$p_class = ( isset( $key['class-p'] ) ) ? ' class="' . $key['class-p'] . '"' : '';

		/**
		 * Run method
		 */
		if ( method_exists( $this->fields_type, $field_method ) ) {

			return '<p' . $p_class . '>' . $this->fields_type->$field_method( $key ) . '</p>';

		} else {

			return '<p' . $p_class . '>' . $this->fields_type->field_type_text( $key ) . '</p>';

		}

	}


	/**
	 * Inject input fields into widget configuration form
	 *
	 * @since 0.1
	 * @wp_hook action in_widget_form
	 *
	 * @param WP_Widget $widget   Widget object.
	 * @param string    $return   The value to return.
	 * @param array     $instance The instance of the widget.
	 *
	 * @return NULL
	 */
	public function input_fields( $widget, $return, $instance ) {
		$instance = $this->get_attributes( $instance );

		foreach ( $this->config as $key => $value ) {
			echo $this->field_type( $widget, $instance, $value ); // XSS ok.
		}

		return null;
	}


	/**
	 * Get default attributes
	 *
	 * @since 0.1
	 *
	 * @param array $instance Widget instance configuration.
	 *
	 * @return array
	 */
	private function get_attributes( $instance ) {

		$instance = wp_parse_args(
			$instance,
			array(
				'widget_css_id'    => '',
				'widget_css_class' => '',
			)
		);

		return $instance;
	}


	/**
	 * Save attributes upon widget saving
	 *
	 * @since 0.1
	 * @wp_hook filter widget_update_callback
	 *
	 * @param array  $instance     Current widget instance configuration.
	 * @param array  $new_instance New widget instance configuration.
	 * @param array  $old_instance Old Widget instance configuration.
	 * @param object $widget       Widget object.
	 *
	 * @return array
	 */
	public function save_attributes( $instance, $new_instance, $old_instance, $widget ) {
		$instance['widget_css_id'] = $instance['widget_css_class'] = '';

		// ID.
		if ( ! empty( $new_instance['widget_css_id'] ) ) {
			$instance['widget_css_id'] = apply_filters(
				'widget_attribute_id',
				sanitize_html_class( $new_instance['widget_css_id'] )
			);
		}

		// Classes.
		if ( ! empty( $new_instance['widget_css_class'] ) ) {
			$instance['widget_css_class'] = apply_filters(
				'widget_attribute_classes',
				implode(
					' ',
					array_map(
						'sanitize_html_class',
						explode( ' ', $new_instance['widget_css_class'] )
					)
				)
			);
		} else {
			$instance['widget_css_class'] = '';
		}

		return $instance;
	}


	/**
	 * Insert attributes into widget markup
	 *
	 * @since 0.1
	 * @filter dynamic_sidebar_params
	 *
	 * @param array $params Widget parameters.
	 *
	 * @return Array
	 */
	public function insert_attributes( $params ) {

		global $wp_registered_widgets;

		$widget_id  = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[ $widget_id ];

		if (
			! isset( $widget_obj['callback'][0] )
			|| ! is_object( $widget_obj['callback'][0] )
		) {
			return $params;
		}

		$widget_options = get_option( $widget_obj['callback'][0]->option_name );
		if ( empty( $widget_options ) ) {
			return $params;
		}

		$widget_num	= $widget_obj['params'][0]['number'];

		if ( empty( $widget_options[ $widget_num ] ) ) {
			return $params;
		}

		$instance = $widget_options[ $widget_num ];

		// ID.
		if ( ! empty( $instance['widget_css_id'] ) ) {
			$params[0]['before_widget'] = preg_replace(
				'/id=".*?"/',
				sprintf( 'id="%s"', $instance['widget_css_id'] ),
				$params[0]['before_widget'],
				1
			);
		}

		// Classes.
		if ( ! empty( $instance['widget_css_class'] ) ) {
			$params[0]['before_widget'] = preg_replace(
				'/class="/',
				sprintf( 'class="%s ', $instance['widget_css_class'] ),
				$params[0]['before_widget'],
				1
			);
		}

		return $params;
	}
}
