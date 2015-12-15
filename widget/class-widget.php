<?php namespace ItalyStrap\Core;
/**
 * Widget API: Widget class
 *
 * @package ItalyStrap
 * @since 1.4.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) die();

use \WP_Widget;

/**
 * Core class used to implement some common method to widget.
 *
 * @since 1.4.0
 *
 * @see WP_Widget
 */
abstract class Widget extends WP_Widget {

	/**
	 * Fields array of widget form
	 * @var array
	 */
	private $fields = array();

	/**
	 * Create Widget and call PHP5 constructor.
	 * Creates a new widget and sets it's labels, description, fields and options.
	 *
	 * @access public
	 * @param array $args The array with widget configuration.
	 */
	public function create_widget( $args ) {

		/**
		 * Settings some defaults options.
		 * @var array
		 */
		$defaults = array(
			'label'				=> '',
			'description'		=> '',
			'fields'			=> array(),
			'widget_options'	=> array(),
			'control_options'	=> array(),
		 );

		/**
		 * Parse and merge args with defaults.
		 * @var array
		 */
		$args = wp_parse_args( (array) $args, (array) $defaults );

		/**
		 * Extract each arg to its own variable.
		 * extract( $args, EXTR_SKIP );
		 */

		/**
		 * Set the widget vars.
		 */
		$id_base    = sanitize_title( $args['label'] );

		$name = $args['label'];

		/**
		 * Check options.
		 * @var array
		 */
		$widget_options = array( 'description' => $args['description'] );

		// if ( ! empty( $args['widget_options'] ) ) $widget_options = array_merge( $widget_options, $args['widget_options'] );

		$widget_options = wp_parse_args( (array) $args['widget_options'], (array) $widget_options );

		/**
		 * The options contains the height, width, and id_base keys.
		 * The height option is never used.
		 * The width option is the width of the fully expanded control form,
		 * but try hard to use the default width.
		 * The id_base is for multi-widgets (widgets which allow multiple instances
		 * such as the text widget), an id_base must be provided.
		 * The widget id will end up looking like {$id_base}-{$unique_number}.
		 * The id_base is optional. See wp_register_widget_control()
		 * https://codex.wordpress.org/Function_Reference/wp_register_widget_control
		 *
		 * Example: array( 'width' => 450 );
		 *
		 * @var array
		 */
		$control_options = $args['control_options'];

		// if ( ! empty( $args['control_options'] ) )
		// 	$control_options = wp_parse_args( (array) $args['control_options'], (array) $control_options );

		/**
		 * Call WP_Widget to create the widget.
		 *
		 * @since 2.8.0
		 * @access public
		 *
		 * @param string $id_base         Optional Base ID for the widget, lowercase and unique.
		 *                                If left empty, a portion of the widget's class name
		 *                                will be used Has to be unique.
		 * @param string $name            Name for the widget displayed on the configuration page.
		 * @param array  $widget_options  Optional. Widget options.
		 *                                See wp_register_sidebar_widget() for information
		 *                                on accepted arguments. Default empty array.
		 * @param array  $control_options Optional. Widget control options.
		 *                                See wp_register_widget_control() for
		 *                                information on accepted arguments. Default empty array.
		 */
		parent::__construct( $id_base, $name, $widget_options, $control_options );

		$this->fields  = $args['fields'];

	}

	/**
	 * Outputs the settings update form.
	 *
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$this->instance = $instance;

		/**
		 * Creates the settings form.
		 * @var string
		 */
		$form = $this->create_fields();

		echo $form; // WPCS: XSS OK.

	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * This function should check that `$new_instance` is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @access public
	 *
	 * @param  array $new_instance New settings for this instance
	 *                             as input by the user via WP_Widget::form().
	 * @param  array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$this->before_update_fields();

		foreach ( $this->fields as $key ) {
			$slug = $key['id'];

			if ( isset( $key['validate'] ) ) {
				if ( false === $this->validate( $key['validate'], $new_instance[ $slug ] ) )
				return $instance;
			}

			if ( isset( $key['filter'] ) )
				$instance[ $slug ] = $this->filter( $key['filter'], $new_instance[ $slug ] );
			else $instance[ $slug ] = strip_tags( $new_instance[ $slug ] );
		}

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		if ( isset( $alloptions[ $this->option_name ] ) )
			delete_option( $this->option_name );

		return $this->after_validate_fields( $instance );
	}

	/**
	 * Fire Before Validate Fields
	 *
	 * Allows to hook code on the update.
	 *
	 * @access public
	 */
	public function before_update_fields() {
		return;
	}

	/**
	 * Fire After Validate Fields
	 *
	 * Allows to modify the output after validating the fields.
	 *
	 * @access public
	 * @param  string $instance The $instance of widget.
	 * @return string           Return the $instance of widget
	 */
	public function after_validate_fields( $instance = '' ) {
		return $instance;
	}

	/**
	 * Validate the value of key
	 *
	 * @access private
	 * @param  string $rules Insert the rule name you want to use for validation.
	 *                       Use | to separate more rules.
	 * @param  string $instance_value The value you want to validate.
	 * @return string                 Return the value validated
	 */
	private function validate( $rules, $instance_value ) {
		$rules = explode( '|', $rules );

		if ( empty( $rules ) || count( $rules ) < 1 )
			return true;

		foreach ( $rules as $rule ) {
			if ( false === $this->do_validation( $rule, $instance_value ) )
			return false;
		}

		return true;
	}

	/**
	 * Filter the value of key
	 *
	 * @access private
	 * @param  string $filters Insert the filter name you want to use.
	 *                         Use | to separate more filter.
	 * @param  string $instance_value The value you want to filter.
	 * @return string                 Return the value filtered
	 */
	private function filter( $filters, $instance_value ) {
		$filters = explode( '|', $filters );

		if ( empty( $filters ) || count( $filters ) < 1 )
			return $instance_value;

		foreach ( $filters as $filter )
			$instance_value = $this->do_filter( $filter, $instance_value );

		return $instance_value;
	}

	/**
	 * Validate the value of key
	 *
	 * @access private
	 * @param  string $rule           Insert the rule name you want to use for validation.
	 * @param  string $instance_value The value you want to validate.
	 * @return string                 Return the value validated
	 */
	private function do_validation( $rule, $instance_value = '' ) {
		switch ( $rule ) {

			case 'alpha':
				return ctype_alpha( $instance_value );
			break;

			case 'alpha_numeric':
				return ctype_alnum( $instance_value );
			break;

			case 'alpha_dash':
				return preg_match( '/^[a-z0-9-_]+$/', $instance_value );
			break;

			case 'numeric':
				return ctype_digit( $instance_value );
			break;

			case 'integer':
				return (bool) preg_match( '/^[\-+]?[0-9]+$/', $instance_value );
			break;

			case 'boolean':
				return is_bool( $instance_value );
			break;

			case 'email':
				return is_email( $instance_value );
			break;

			case 'decimal':
				return (bool) preg_match( '/^[\-+]?[0-9]+\.[0-9]+$/', $instance_value );
			break;

			case 'natural':
				return (bool) preg_match( '/^[0-9]+$/', $instance_value );
			return;

			case 'natural_not_zero':
				if ( ! preg_match( '/^[0-9]+$/', $instance_value ) ) return false;
				if ( 0 === $instance_value ) return false;
				return true;
			return;

			default:
				if ( method_exists( $this, $rule ) )
					return $this->$rule( $instance_value );
				else return false;
			break;

		}
	}

	/**
	 * Filter the value of key
	 *
	 * @access private
	 * @param  string $filter         The filter name you want to use.
	 * @param  string $instance_value The value you want to filter.
	 * @return string                 Return the value filtered
	 */
	private function do_filter( $filter, $instance_value = '' ) {
		switch ( $filter ) {

			case 'strip_tags':
				return strip_tags( $instance_value );
			break;

			case 'wp_strip_all_tags':
				return wp_strip_all_tags( $instance_value );
			break;

			case 'esc_attr':
				return esc_attr( $instance_value );
			break;

			case 'esc_url':
				return esc_url( $instance_value );
			break;

			case 'esc_textarea':
				return esc_textarea( $instance_value );
			break;

			default:
				if ( method_exists( $this, $filter ) )
					return $this->$filter( $instance_value );
				else return $instance_value;
			break;
		}
	}

	/**
	 * Create Fields
	 *
	 * Creates each field defined.
	 *
	 * @access protected
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Fields
	 */
	protected function create_fields( $out = '' ) {

		$out = $this->before_create_fields( $out );

		if ( ! empty( $this->fields ) ) {
			foreach ( $this->fields as $key )
				$out .= $this->create_field( $key );
		}

		$out = $this->after_create_fields( $out );

		return $out;
	}

	/**
	 * Fire Before Create Fields
	 *
	 * Allows to modify code before creating the fields.
	 *
	 * @access protected
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Fields
	 */
	protected function before_create_fields( $out = '' ) {
		return $out;
	}

	/**
	 * Fire After Create Fields
	 *
	 * Allows to modify code after creating the fields.
	 *
	 * @access protected
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Fields
	 */
	protected function after_create_fields( $out = '' ) {
		return $out;
	}

	/**
	 * Create the Fields
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Fields
	 */
	protected function create_field( $key, $out = '' ) {

		/* Set Defaults */
		$key['default'] = isset( $key['default'] ) ? $key['default'] : '';

		$slug = $key['id'];

		if ( isset( $this->instance[ $slug ] ) )
			$key['value'] = empty( $this->instance[ $slug ] ) ? '' : strip_tags( $this->instance[ $slug ] );
		else unset( $key['value'] );

		/* Set field id and name  */
		$key['_id'] = $this->get_field_id( $slug );
		$key['_name'] = $this->get_field_name( $slug );

		/* Set field type */
		if ( ! isset( $key['type'] ) ) $key['type'] = 'text';

		/* Prefix method */
		$field_method = 'create_field_' . str_replace( '-', '_', $key['type'] );

		/* Check for <p> Class */
		$p_class = ( isset( $key['class-p'] ) ) ? ' class="'.$key['class-p'].'"' : '';

		/* Run method */
		if ( method_exists( $this, $field_method ) )
			return '<p' . $p_class . '>' . $this->$field_method( $key ) . '</p>';

	}

	/**
	 * Create the Field Text
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	protected function create_field_text( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<input type="text" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) )
			$out .= 'size="' . esc_attr( $key['size'] ) . '" ';

		$out .= ' />';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the Field Textarea
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Textarea
	 */
	protected function create_field_textarea( $key, $out = '' ) {
		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<textarea ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		if ( isset( $key['rows'] ) )
			$out .= 'rows="' . esc_attr( $key['rows'] ) . '" ';

		if ( isset( $key['cols'] ) )
			$out .= 'cols="' . esc_attr( $key['cols'] ) . '" ';

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="'. esc_attr( $key['_id'] ) .'" name="' . esc_attr( $key['_name'] ) . '">'.esc_html( $value );

		$out .= '</textarea>';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the Field Checkbox
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Checkbox
	 */
	protected function create_field_checkbox( $key, $out = '' ) {

		$out .= ' <input type="checkbox" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="1" ';

		if ( ( isset( $key['value'] ) && 1 === $key['value'] ) || ( ! isset( $key['value'] ) && 1 === $key['default'] ) )
			$out .= ' checked="checked" ';

		$out .= ' /> ';

		$out .= $this->create_field_label( $key['name'], $key['_id'] );

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the Field Select
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	protected function create_field_select( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr__( $field ) . '" ';

			if ( $selected === $field )
				$out .= ' selected="selected" ';

			$out .= '> '.esc_html( $option ).'</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the Field Select with Options Group
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select with Options Group
	 */
	protected function create_field_select_group( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $group => $options ) {

			$out .= '<optgroup label="' . $group . '">';

			foreach ( $options as $field => $option ) {

				$out .= '<option value="' . esc_attr( $field ) . '" ';

				if ( esc_attr( $selected ) === $field )
					$out .= ' selected="selected" ';

				$out .= '> ' . esc_html( $option ) . '</option>';
			}

			$out .= '</optgroup>';

		}

		$out .= '</select>';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the field number
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field number
	 */
	protected function create_field_number( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<input type="number" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) )
			$out .= 'size="' . esc_attr( $key['size'] ) . '" ';

		$out .= ' />';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the field description
	 *
	 * @access protected
	 * @param  string $desc The description.
	 * @return string       Return the description
	 */
	protected function create_field_description( $desc ) {

		return  '<br/><small class="description">' . esc_html( $desc ) . '</small>';

	}

	/**
	 * Create the field label
	 *
	 * @access protected
	 * @param  string $name The labels name.
	 * @param  string $id   The labels ID.
	 * @return string       Return the labels
	 */
	protected function create_field_label( $name = '', $id = '' ) {

		return '<label for="' . esc_attr( $id ). '">' . esc_html( $name ) . ':</label>';

	}

	/**
	 * Upload the Javascripts for the media uploader in widget config
	 *
	 * @param string $hook The name of the page.
	 */
	public function upload_scripts( $hook ) {

		if ( 'widgets.php' !== $hook ) {
			return;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {

			wp_enqueue_media();

		} else {

			if ( ! wp_script_is( 'thickbox', 'enqueued' ) ) {

				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'thickbox' );

			}

			if ( ! wp_script_is( 'media-upload', 'enqueued' ) )
				wp_enqueue_script( 'media-upload' );

		}

		wp_enqueue_script( 'jquery-ui-sortable' );

		$js_file = ( WP_DEBUG ) ? 'admin/js/src/widget.js' : 'admin/js/widget.min.js';

		if ( ! wp_script_is( 'italystrap-widget' ) ) {

			wp_enqueue_style( 'italystrap-widget', ITALYSTRAP_PLUGIN_URL . 'admin/css/widget.css' );

			wp_enqueue_script(
				'italystrap-widget',
				ITALYSTRAP_PLUGIN_URL . $js_file,
				array( 'jquery', 'jquery-ui-sortable' )
			);

		}

	}

	/**
	 * Flush widget cache
	 */
	function flush_widget_cache() {

		wp_cache_delete( $this->option_name, 'widget' );
	}
}
