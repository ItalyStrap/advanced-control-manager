<?php namespace ItalyStrap\Core;
/**
 * Widget API: Widget class
 *
 * @package ItalyStrap
 * @since 1.4.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

use \WP_Widget;

/**
 * Core class used to implement some common method to widget.
 *
 * @since 2.0.0
 *
 * @see WP_Widget
 */
abstract class Widget extends WP_Widget {

	/**
	 * Fields array of widget form
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Sections fields of widget forms
	 *
	 * @var array
	 */
	private $sections_keys = array();

	/**
	 * Set the title field
	 */
	protected function title_field() {

		return array(
			'title'	=> array(
				'name'		=> __( 'Title', 'ItalyStrap' ),
				'desc'		=> __( 'Enter the widget title.', 'ItalyStrap' ),
				'id'		=> 'title',
				'type'		=> 'text',
				'class'		=> 'widefat',
				'default'	=> '',
				'validate'	=> 'alpha_dash',
				'filter'	=> 'strip_tags|esc_attr',
			),
		);
	}

	/**
	 * Echoes the widget content.
	 *
	 * Sub-classes should over-ride this function to generate their widget code.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		$output = '';

		$output .= $args['before_widget'];

		/**
		 * Print the optional widget title
		 */
		$output .= $this->widget_title( $args, $instance );

		/**
		 * Modify this for outputting the HTML
		 */
		$output .= $this->widget_render( $args, $instance );

		$output .= $args['after_widget'];

		echo apply_filters( 'widget_text', $this->cache_widget( $args, $output ) ); // XSS ok.

	}

	/**
	 * Dispay the widget content
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 */
	public function widget_render( $args, $instance ) {}

	/**
	 * Dispay the optional title
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param  array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return string          Return the optional title
	 */
	public function widget_title( $args, $instance ) {

		/**
		 * The code above is to testing in case I want to add link to widget title
		 */
		// $title = apply_filters( 'widget_title', empty( $this->args['title'] ) ? '' : $this->args['title'], $this->args, $this->id_base );

		// if ( $title && $this->args['title_link'] )
		// 	echo $before_title . apply_filters( 'italystrap_widget_title_link', '<a href="' . esc_html( $this->args['title_link'] ) . '">' . esc_attr( $title ) . '</a>', $this->args['title_link'], $title ) . $after_title;
		// elseif ( $title && ! $this->args['title_link'] )
		// 	echo $before_title . esc_attr( $title ) . $after_title;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters(
			'widget_title',
			empty( $instance['title'] ) ? '' : $instance['title'],
			$instance,
			$this->id_base
		);

		/**
		 * Return the optional widget title with before_title and after_title
		 */
		if ( $title )
			$title = $args['before_title'] . $title . $args['after_title']; // XSS ok.

		return $title;

	}

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
		 *
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
		 *
		 * @var array
		 */
		$args = wp_parse_args( (array) $args, (array) $defaults );

		/**
		 * Convert $args['label'] spaces with dash '-'.
		 */
		$id_base = sanitize_title( $args['label'] );

		$name = $args['label'];

		/**
		 * Set the default widget title
		 */
		$args['fields']['title']['default'] = $name;

		/**
		 * Check options.
		 *
		 * @var array
		 */
		$widget_options = array( 'description' => $args['description'] );

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

		$this->fields  = $args['fields'];

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

		add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

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
		 *
		 * @var string
		 */
		$form = $this->render_form();

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

		$instance = $new_instance;

		$this->before_update_fields();

		foreach ( $this->fields as $key ) {

			if ( isset( $key['validate'] ) ) {

				if ( false === $this->validate( $key['validate'], $instance[ $key['id'] ] ) ) {

					$instance[ $key['id'] ] = '';
				}
			}

			if ( isset( $key['filter'] ) )
				$instance[ $key['id'] ] = $this->filter( $key['filter'], $instance[ $key['id'] ] );
			else $instance[ $key['id'] ] = strip_tags( $instance[ $key['id'] ] );

		}

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
	public function after_validate_fields( $instance ) {

		return $instance;

	}

	/**
	 * Validate the value of key
	 *
	 * @access private
	 * @param  string $rules          Insert the rule name you want to use for validation.
	 *                                Use | to separate more rules.
	 * @param  string $instance_value The value you want to validate.
	 * @return bool                   Return true if valid and folse if it is not
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

			case 'ctype_alpha':
				return ctype_alpha( $instance_value );
			break;

			case 'ctype_alnum':
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

			case 'sanitize_email':
				return sanitize_email( $instance_value );
			break;

			case 'sanitize_file_name':
				return sanitize_file_name( $instance_value );
			break;

			case 'sanitize_html_class':
				return sanitize_html_class( $instance_value );
			break;

			case 'sanitize_key':
				return sanitize_key( $instance_value );
			break;

			case 'sanitize_meta':
				return sanitize_meta( $instance_value );
			break;

			case 'sanitize_mime_type':
				return sanitize_mime_type( $instance_value );
			break;

			case 'sanitize_option':
				return sanitize_option( $instance_value );
			break;

			case 'sanitize_sql_orderby':
				return sanitize_sql_orderby( $instance_value );
			break;

			case 'sanitize_text_field':
				return sanitize_text_field( $instance_value );
			break;

			case 'sanitize_title':
				return sanitize_title( $instance_value );
			break;

			case 'sanitize_title_for_query':
				return sanitize_title_for_query( $instance_value );
			break;

			case 'sanitize_title_with_dashes':
				return sanitize_title_with_dashes( $instance_value );
			break;

			case 'sanitize_user':
				return sanitize_user( $instance_value );
			break;

			default:
				if ( method_exists( $this, $filter ) )
					return $this->$filter( $instance_value );
				else return $instance_value;
			break;
		}
	}

	private function make_sections_array() {

		$sections = array();

		foreach ( (array) $this->fields as $key ) {

			if ( isset( $key['section'] ) && 'general' !== $key['section'] ) {

				$sections[ $key['section'] ][ $key['id'] ] = (array) $key;

			} else {

				$sections['general'][ $key['id'] ] = (array) $key;

			}
		}

		return $sections;

	}

	private function get_sections_keys( array $sections ) {

		return array_keys( $sections );

	}

	protected function create_sections_tabs_menu( array $sections ) {

		$tabs = '<div class="upw-tabs">';
		$i = 0;

		foreach ( $this->sections_keys as $key ) {
			$tabs .= '<a class="upw-tab-item ' . ( ( 0 === $i ) ? 'active' : '' ) . '" data-toggle="upw-tab-' . $key . '">' . ucfirst( $key ) . '</a>';
			$i++;
		}

		$tabs .= '</div>';

		return $tabs;

	}

	protected function create_sections_tabs( array $sections ) {

		$out = '';
		$i = 0;

		foreach ( $this->sections_keys as $key => $value ) {

			$out .= '<div class="upw-tab' . ( ( 0 === $i ) ? '' : ' upw-hide' ) . ' upw-tab-' . $value . '">';

			foreach ( $sections[ $value ] as $key ) {

				$out .= $this->create_field( $key );

			}

			$out .= '</div>';
			$i++;

		}

		return $out;

	}

	protected function render_form( $out = '' ) {

		$sections = $this->make_sections_array();

		$this->sections_keys = $this->get_sections_keys( $sections );

		$out = $this->before_create_fields( $out );

		if ( count( $this->sections_keys ) <= 1 ) {

			$out .= $this->create_fields();

		} else {

			$out .= $this->create_sections_tabs_menu( $sections );

			$out .= $this->create_sections_tabs( $sections );

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
	 * Create Fields
	 *
	 * Creates each field defined.
	 *
	 * @access protected
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Fields
	 */
	protected function create_fields( $out = '' ) {

		foreach ( $this->fields as $key )
			$out .= $this->create_field( $key );

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
	protected function create_field( array $key, $out = '' ) {

		/* Set Defaults */
		$key['default'] = isset( $key['default'] ) ? $key['default'] : '';

		if ( isset( $this->instance[ $key['id'] ] ) )
			$key['value'] = empty( $this->instance[ $key['id'] ] ) ? '' : strip_tags( $this->instance[ $key['id'] ] );
		else unset( $key['value'] );

		/* Set field id and name  */
		$key['_id'] = $this->get_field_id( $key['id'] );
		$key['_name'] = $this->get_field_name( $key['id'] );

		/* Set field type */
		if ( ! isset( $key['type'] ) ) $key['type'] = 'text';

		/* Prefix method */
		$field_method = 'create_field_' . str_replace( '-', '_', $key['type'] );

		/* Check for <p> Class */
		$p_class = ( isset( $key['class-p'] ) ) ? ' class="' . $key['class-p'] . '"' : '';

		/* Run method */
		if ( method_exists( $this, $field_method ) )
			return '<p' . $p_class . '>' . $this->$field_method( $key ) . '</p>';
		else return '<p' . $p_class . '>' . $this->create_field_text( $key ) . '</p>';

	}

	/**
	 * Combines attributes into a string for a form element
	 *
	 * @since  1.1.0
	 * @param  array $attrs        Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 * @return string               String of attributes for form element
	 */
	public function concat_attrs( $attrs, $attr_exclude = array() ) {
		$attributes = '';
		foreach ( $attrs as $attr => $val ) {
			$excluded = in_array( $attr, (array) $attr_exclude, true );
			$empty    = false === $val && 'value' !== $attr;
			if ( ! $excluded && ! $empty ) {
				// If data attribute, use single quote wraps, else double.
				$quotes = stripos( $attr, 'data-' ) !== false ? "'" : '"';
				$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
			}
		}
		return $attributes;
	}

	/**
	 * Handles outputting an 'input' element
	 *
	 * @link http://html5doctor.com/html5-forms-input-types/
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 * @return string     Form input element
	 */
	public function input( $attr = array(), $key = array() ) {

		$a = array(
			'type'            => 'text',
			'class'           => esc_attr( $key['class'] ),
			'name'            => esc_attr( $key['_name'] ),
			'id'              => esc_attr( $key['_id'] ),
			'value'           => ( isset( $key['value'] ) ? $key['value'] : ( isset( $key['default'] ) ? $key['default'] : '' ) ),
			'desc'            => $this->create_field_description( $key['desc'] ),
			'js_dependencies' => array(),
		);

		if ( ! empty( $a['js_dependencies'] ) ) {
			CMB2_JS::add_dependencies( $a['js_dependencies'] );
		}

		return sprintf( '<input%s/>%s', $this->concat_attrs( $a, array( 'desc', 'js_dependencies' ) ), $a['desc'] );
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

		return $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( array(), $key );
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

		if ( ( isset( $key['value'] ) && '1' === $key['value'] ) || ( ! isset( $key['value'] ) && 1 === $key['default'] ) )
			$out .= ' checked="checked" ';

		/**
		 * Da vedere se utilizzabile per fare il controllo sulle checkbox.
		 * if ( isset( $key['value'] ) && 'true' === $key['value'] ) {
		 * 	$key['value'] = true;
		 * 	} else $key['value'] = false;
		 *
		 * $out .= checked( $key['value'], true );
		 */

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

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) )
			$out .= $this->create_field_description( $key['desc'] );

		return $out;
	}

	/**
	 * Create the Field Multiple Select
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	protected function create_field_multiple_select( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) )
			$out .= 'class="' . esc_attr( $key['class'] ) . '" ';

		$out .= 'size="6" multiple> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr__( $field ) . '" ';

			if ( $selected === $field )
				$out .= ' selected="selected" ';

			$out .= '> ' . esc_html( $option ) . '</option>';

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
	 * Create the field email
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field email
	 */
	protected function create_field_email( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<input type="email" ';

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
	 * Create the field url
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field url
	 */
	protected function create_field_url( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<input type="url" ';

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
	 * Create the field tel
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field tel
	 */
	protected function create_field_tel( $key, $out = '' ) {

		$out .= $this->create_field_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<input type="tel" ';

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
	 * Create the Field Text
	 *
	 * @access protected
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	protected function create_field_media_list( $key, $out = '' ) {

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

		ob_start();

		?>
			<h5><?php esc_attr_e( 'Add your images', 'ItalyStrap' ); ?></h5>
			<hr>
			<div class="media_carousel_sortable">
				<ul id="sortable" class="carousel_images">
				<?php if ( ! empty( $value ) ) : ?>
					<?php
					$ids = explode( ',', $value );

					foreach ( $ids as $id ) :

						$attr = array(
							'data-id'	=> $id,
						);
						$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );

						if ( '' === $output ) {
							$id = (int) get_post_thumbnail_id( $id );
							$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );
						}

						if ( $output ) :
					?>
				
						<li class="carousel-image ui-state-default">
							<div>
								<i class="dashicons dashicons-no"></i>
								<?php echo $output; // XSS ok. ?>
							</div>
						</li>
				
					<?php
						endif;
					endforeach; ?>
				<?php endif; ?>
				</ul>
			</div>
			<span style="clear:both;"></span>
			<input class="upload_carousel_image_button button button-primary widefat" type="button" value="<?php esc_attr_e( 'Add images', 'ItalyStrap' ); ?>" />
		<hr>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $out . $output;
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
	 * @todo Sistemare gli script da caricare per i vari widget nel pannello admin
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
	 * Get cached widget.
	 *
	 * @param  array $args The argument of widget class.
	 * @return bool true   If the widget is cached otherwise false
	 */
	public function get_cached_widget( $args ) {

		$cache = wp_cache_get( apply_filters( 'italystrap_cached_widget_id', $this->id ), 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = null;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ]; // XSS ok.
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget.
	 *
	 * @param  array  $args     The argumento of widget class.
	 * @param  string $content The content of the widget to display.
	 * @return string          The content that was cached
	 */
	public function cache_widget( $args, $content ) {

		wp_cache_set( apply_filters( 'italystrap_cached_widget_id', $this->id ), array( $args['widget_id'] => $content ), 'widget' );

		return $content;

	}

	/**
	 * Flush the cache.
	 */
	public function flush_widget_cache() {

		wp_cache_delete( apply_filters( 'italystrap_cached_widget_id', $this->id ), 'widget' );

	}
}
