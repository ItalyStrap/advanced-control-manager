<?php namespace ItalyStrap\Core;
/**
 * Widget API: Widget class
 *
 * @package ItalyStrap
 * @since 2.0.0
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
	 * The type of fields to create
	 * @var object
	 */
	private $fields_type;

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
	 * Get the fields for widget
	 * @param  array  $fields The options array with new fields
	 * @return array          Return an array with all fields
	 */
	public function get_widget_fields( $fields = array() ) {

		return array_merge( $this->title_field(), $fields );

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
	abstract public function widget_render( $args, $instance );

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

		$output = '';

		/**
		 * This filter is documented in wp-includes/widgets/class-wp-widget-pages.php
		 *
		 * @var string
		 */
		$title = apply_filters(
			'widget_title',
			empty( $instance['title'] ) ? '' : $instance['title'],
			$instance,
			$this->id_base
		);

		/**
		 * Return the optional widget title with before_title and after_title
		 */
		if ( $title ) {

			$output = $args['before_title'];

			if ( ! empty( $this->args['title_link'] ) ) {

				$output .= '<a href="' . esc_html( $this->args['title_link'] ) . '">' . $title . '</a>';

			} else {

				$output .= $title;

			}

			$output .= $args['after_title'];

		}

		return $output;

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

		/**
		 * With this filter you can change the default widget fields
		 *
		 * @var array
		 */
		$this->fields  = apply_filters( 'italystrap_widget_fields', $args['fields'], $id_base );

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

		$this->fields_type = new Fields;

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

			if ( isset( $key['filter'] ) ) {
				$instance[ $key['id'] ] = $this->filter( $key['filter'], $instance[ $key['id'] ] );
			} else { $instance[ $key['id'] ] = strip_tags( $instance[ $key['id'] ] ); }
		}

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		if ( isset( $alloptions[ $this->id ] ) ) {
			delete_option( $this->id );
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

		if ( empty( $rules ) || count( $rules ) < 1 ) {
			return true; }

		foreach ( $rules as $rule ) {
			if ( false === $this->do_validation( $rule, $instance_value ) ) {
				return false; }
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

		if ( empty( $filters ) || count( $filters ) < 1 ) {
			return $instance_value; }

		foreach ( $filters as $filter ) {
			$instance_value = $this->do_filter( $filter, $instance_value ); }

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
				if ( ! preg_match( '/^[0-9]+$/', $instance_value ) ) { return false; }
				if ( 0 === $instance_value ) { return false; }
				return true;
			return;

			default:
				if ( method_exists( $this, $rule ) ) {
					return $this->$rule( $instance_value );
				} else { return false; }
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
				if ( method_exists( $this, $filter ) ) {
					return $this->$filter( $instance_value );
				} else { return $instance_value; }
			break;
		}
	}

	/**
	 * Create the section array for tab in widget panel
	 *
	 * @return array Return the array for section
	 */
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

	/**
	 * Get the sections array key
	 *
	 * @param  array $sections The sections array.
	 * @return array           Return an array with sections key
	 */
	private function get_sections_keys( array $sections ) {

		return array_keys( $sections );

	}

	/**
	 * Create the sections tabs menu in widget panel
	 *
	 * @param  array $sections The sections array.
	 * @return string          Return the HTML for tabs menu in widget panel
	 */
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

	/**
	 * Create the sections
	 *
	 * @param  array $sections The sections array.
	 * @return string          Return the HTML with sections for widget panel
	 */
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

	/**
	 * Create the HTML form in widget panel
	 *
	 * @param  string $out The output variable. Default empy.
	 * @return string      Return the HTML for form inputs in widget panel
	 */
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

		foreach ( $this->fields as $key ) {
			$out .= $this->create_field( $key ); }

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

		if ( isset( $this->instance[ $key['id'] ] ) ) {
			$key['value'] = empty( $this->instance[ $key['id'] ] ) ? '' : strip_tags( $this->instance[ $key['id'] ] );
		} else { unset( $key['value'] ); }

		/* Set field id and name  */
		$key['_id'] = $this->get_field_id( $key['id'] );
		$key['_name'] = $this->get_field_name( $key['id'] );

		/* Set field type */
		if ( ! isset( $key['type'] ) ) { $key['type'] = 'text'; }

		/* Prefix method */
		$field_method = 'create_field_' . str_replace( '-', '_', $key['type'] );

		/* Check for <p> Class */
		$p_class = ( isset( $key['class-p'] ) ) ? ' class="' . $key['class-p'] . '"' : '';

		// $this->fields_type
		// 
		// var_dump( $this->fields_type->$field_method( $key ) );

		/**
		 * if ( method_exists( $this, $field_method ) ) {
		 * 	return '<p' . $p_class . '>' . $this->$field_method( $key ) . '</p>';
		 * } else {
		 * return '<p' . $p_class . '>' . $this->create_field_text( $key ) . '</p>'; }
		 */

		/**
		 * Run method
		 */
		if ( method_exists( $this->fields_type, $field_method ) ) {

			return '<p' . $p_class . '>' . $this->fields_type->$field_method( $key ) . '</p>';

		} else {

			return '<p' . $p_class . '>' . $this->fields_type->create_field_text( $key ) . '</p>';
			
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

			if ( ! wp_script_is( 'media-upload', 'enqueued' ) ) {
				wp_enqueue_script( 'media-upload' ); }
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
}
