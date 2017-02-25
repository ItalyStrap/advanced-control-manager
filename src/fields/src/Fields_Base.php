<?php
/**
 * Abstract class for Fields
 *
 * This is the abstract class for Fields functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Fields;

use InvalidArgumentException;

/**
 * Abstract class for fields functionality
 */
abstract class Fields_Base implements Fields_Interface {

	/**
	 * Determine whether this field should show, based on the 'show_on_cb' callback.
	 * Forked from CMB2
	 * @see CMB2_Field.php
	 *
	 * @since 2.0.0
	 *
	 * @param  array $key      The array with field arguments.
	 *
	 * @return bool Whether the field should be shown.
	 */
	public function should_show( $key ) {
		
		/**
		 * Default. Show the field
		 *
		 * @var bool
		 */
		$show = true;

		if ( ! isset( $key[ 'show_on_cb' ] ) ) {
			return $show;
		}

		/**
		 * Use the callback to determine showing the field, if it exists
		 */
		if ( is_callable( $key[ 'show_on_cb' ] ) ) {
			$show = call_user_func( $key[ 'show_on_cb' ], $this );
		}

		return (bool) $show;
	}

	/**
	 * Set _id _name attributes
	 *
	 * @todo Creare codice per settare _id e _name in caso non siano forniti
	 *       come chiave -> valore con l'array $key.
	 *       Attualmente sono generati dalle classi che gestiscono widget e admin
	 *       Vedere: Widget::field_type();
	 *       Vedere: Admin::get_field_type();
	 *       Valutare se passare per referenza il valore
	 *
	 * @param  array $key The array with field arguments.
	 * @return array      The new array with _id and _name set.
	 */
	public function set_attr_id_name( array &$key ) {

		//italystrap_settings
		if ( empty( $this->args['options_name'] ) ) {
			$this->args['options_name'] = 'italystrap_settings';
		}

		/**
		 * Set field id and name
		 */
		$key['_id'] = $key['_name'] = $this->args['options_name'] . '[' . $key['id'] . ']';

		return $key;
	
	}

	/**
	 * Get the field type
	 *
	 * @param  array $key      The array with field arguments.
	 * @param  array $instance This is the $instance variable of widget
	 *                         or the options variable of the plugin.
	 *
	 * @return string           Return the field html
	 */
	public function get_field_type( array $key, array $instance ) {

		if ( ! isset( $key['_id'] ) ) {
			throw new InvalidArgumentException( __( 'The _id key is not set', 'italystrap' ) );
		}

		if ( ! isset( $key['_name'] ) ) {
			throw new InvalidArgumentException( __( 'The _name key is not set', 'italystrap' ) );
		}

		/**
		 * If field is requesting to be conditionally shown
		 */
		if ( ! $this->should_show( $key ) ) {
			return '';
		}

		/**
		 * Set field type
		 */
		if ( ! isset( $key['type'] ) ) {
			$key['type'] = 'text';
		}

		/**
		 * Prefix method
		 *
		 * @var string
		 */
		$field_method = 'field_type_' . str_replace( '-', '_', $key['type'] );

		/**
		 * Set Defaults
		 */
		$key['default'] = isset( $key['default'] ) ? ( (string) $key['default'] ) : '';

		if ( isset( $instance[ $key['id'] ] ) ) {
			/**
			 * Non ricordo perché ho fatto la if else sotto ad ogni modo il valore è già escaped quando è stampato dal metodo dedicato quindi non serve ma lo tengo per fare ulteriori test in futuro.
			 * Con la text area il valore deve essere passato senza nessuna validazione se no non stampa l'html.
			 */
			$key['value'] = $instance[ $key['id'] ];

			// if ( is_array( $instance[ $key['id'] ] ) ) {
			// 	$key['value'] = $instance[ $key['id'] ];

			// } else {
			// 	$key['value'] = strip_tags( $instance[ $key['id'] ] );
			// }
		} else {
			$key['value'] = null;
		}

		/**
		 * CSS class for <p>
		 *
		 * @var string
		 */
		$p_class = isset( $key['class-p'] ) ? ' class="' . $key['class-p'] . '"' : '';

		/**
		 * The field html
		 *
		 * @var string
		 */
		$output = '';

		/**
		 * Run method
		 */
		$output = sprintf(
			'<p%1$s>%2$s</p>',
			$p_class,
			method_exists( $this, $field_method ) ? $this->$field_method( $key ) : $this->field_type_text( $key )
		);

		return $output;
	
	}

	/**
	 * Combines attributes into a string for a form element
	 *
	 * @since  2.0.0
	 * @param  array $attrs        Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 *
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
	 *
	 * @return string     Form input element
	 */
	public function input( array $attr = array(), array $key = array() ) {

		if ( isset( $key['attributes'] ) ) {
			$attr = wp_parse_args( $attr, (array) $key['attributes'] );
		}

		$a = wp_parse_args( $attr, array(
			'type'				=> 'text',
			'class'				=> esc_attr( isset( $key['class'] ) ? $key['class'] : 'none' ),
			'name'				=> esc_attr( $key['_name'] ),
			'id'				=> esc_attr( $key['_id'] ),
			'value'				=> isset( $key['value'] ) ? esc_attr( $key['value'] ) : ( isset( $key['default'] ) ? esc_attr( $key['default'] ) : '' ),
			'desc'				=> $this->field_type_description( $key['desc'] ),
			'js_dependencies'	=> array(),
		) );

		if ( isset( $key['size'] ) ) {
			$a['size'] = esc_attr( $key['size'] );
		}

		if ( isset( $key['placeholder'] ) ) {
			$a['placeholder'] = esc_attr( $key['placeholder'] );
		}

		// if ( ! empty( $a['js_dependencies'] ) ) {
		// 	CMB2_JS::add_dependencies( $a['js_dependencies'] );
		// }

		return sprintf( '<input%s/>%s', $this->concat_attrs( $a, array( 'desc', 'js_dependencies' ) ), $a['desc'] );
	}

	/**
	 * Handles outputting an 'textarea' element
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 *
	 * @return string      Form textarea element
	 */
	public function textarea( array $attr = array(), array $key = array() ) {
		$a = wp_parse_args( $attr, array(
			'class' => esc_attr( isset( $key['class'] ) ? $key['class'] : '' ),
			'name'  => esc_attr( $key['_name'] ),
			'id'    => esc_attr( $key['_id'] ),
			'cols'  => '60',
			'rows'  => '10',
			'value' => esc_attr( isset( $key['value'] ) ? $key['value'] : ( isset( $key['default'] ) ? $key['default'] : '' ) ),
			'desc'  => $this->field_type_description( $key['desc'] ),
		) );
		return sprintf( '<textarea%s>%s</textarea>%s', $this->concat_attrs( $a, array( 'desc', 'value' ) ), $a['value'], $a['desc'] );
	}

	/**
	 * Get element with image for media fields
	 *
	 * @param  int $id The ID of the image.
	 * @param  string $text The text.
	 * @return string        The HTML of the element with image
	 */
	public function get_el_media_field( $id ) {
	
		$attr = array(
			'data-id'	=> $id,
		);
		$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );

		if ( '' === $output ) {
			$id = (int) get_post_thumbnail_id( $id );
			$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );
		}

		if ( $output ) {
			echo '<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i>' . $output . '</div></li>';// XSS ok.
		}
	}
}
