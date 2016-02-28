<?php namespace ItalyStrap\Core;
/**
 * Fields API: Fields Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Class for make field type
 */
class Fields {

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

		$a = wp_parse_args( $attr, array(
			'type'            => 'text',
			'class'           => esc_attr( $key['class'] ),
			'name'            => esc_attr( $key['_name'] ),
			'id'              => esc_attr( $key['_id'] ),
			'value'           => esc_attr( isset( $key['value'] ) ? $key['value'] : ( isset( $key['default'] ) ? $key['default'] : '' ) ),
			'desc'            => $this->field_type_description( $key['desc'] ),
			'js_dependencies' => array(),
		) );

		if ( isset( $key['size'] ) ) {
			$a['size'] = esc_attr( $key['size'] );
		}

		// if ( ! empty( $a['js_dependencies'] ) ) {
		// 	CMB2_JS::add_dependencies( $a['js_dependencies'] );
		// }

		return sprintf( '<input%s/>%s', $this->concat_attrs( $a, array( 'desc', 'js_dependencies' ) ), $a['desc'] );
	}

	/**
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function field_type_text( $key, $out = '' ) {
		
		$attr = array();

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function field_type_hidden( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'hidden',
			'desc'	=> '',
			);

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the field number
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field number
	 */
	public function field_type_number( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'number',
			);

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the field email
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field email
	 */
	public function field_type_email( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'email',
			);

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the field url
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field url
	 */
	public function field_type_url( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'url',
			);

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the field tel
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML field tel
	 */
	public function field_type_tel( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'tel',
			);

		return $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );
	}

	/**
	 * Create the Field Text
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Text
	 */
	public function field_type_media_list( $key, $out = '' ) {

		$attr = array(
			'type'	=> 'text',
			);

		$out = $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>' . $this->input( $attr, $key );

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

		$out .= ob_get_contents();
		ob_end_clean();

		return $out;
	}

	/**
	 * Create the Field Textarea
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Textarea
	 */
	public function field_type_textarea( $key, $out = '' ) {
		$out .= $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<textarea ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		if ( isset( $key['rows'] ) ) {
			$out .= 'rows="' . esc_attr( $key['rows'] ) . '" '; }

		if ( isset( $key['cols'] ) ) {
			$out .= 'cols="' . esc_attr( $key['cols'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="'. esc_attr( $key['_id'] ) .'" name="' . esc_attr( $key['_name'] ) . '">' . esc_html( $value );

		$out .= '</textarea>';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Checkbox
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Checkbox
	 */
	public function field_type_checkbox( $key, $out = '' ) {

		$out .= ' <input type="checkbox" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="1" ';

		if ( ( isset( $key['value'] ) && '1' === $key['value'] ) || ( ! isset( $key['value'] ) && 1 === $key['default'] ) ) {
			$out .= ' checked="checked" '; }

		/**
		 * Da vedere se utilizzabile per fare il controllo sulle checkbox.
		 * if ( isset( $key['value'] ) && 'true' === $key['value'] ) {
		 * 	$key['value'] = true;
		 * 	} else $key['value'] = false;
		 *
		 * $out .= checked( $key['value'], true );
		 */

		$out .= ' /> ';

		$out .= $this->field_type_label( $key['name'], $key['_id'] );

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function field_type_select( $key, $out = '' ) {

		$out .= $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'ItalyStrap' ) ;
			$key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
		}

		foreach ( (array) $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr( $field ) . '" ';

			if ( $selected === $field ) {
				$out .= ' selected="selected" '; }

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Multiple Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function field_type_multiple_select( $key, $out = '' ) {

		$out .= $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'size="6" multiple> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'ItalyStrap' ) ;
			$key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
		}

		foreach ( (array) $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr( $field ) . '" ';

			if ( $selected === $field ) {
				$out .= ' selected="selected" '; }

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Multiple Select
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select
	 */
	public function field_type_taxonomy_multiple_select( $key, $out = '' ) {

		$out .= $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '[]" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'size="6" multiple> ';

		$selected = ! empty( $key['value'] ) ? $key['value'] : array();

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'ItalyStrap' ) ;
			$out .= '<option value="0"> ' . esc_html( $none ) . '</option>';
		}

		$tax_arrays = get_terms( $key['taxonomy'] );

		foreach ( (array) $tax_arrays as $tax_obj ) {

			$out .= '<option value="' . esc_attr( $tax_obj->term_id ) . '" ';

			if ( in_array( $tax_obj->term_id, (array) $selected ) ) {
				$out .= ' selected="selected" ';
			}

			$out .= '> ' . esc_html( $tax_obj->name ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the Field Select with Options Group
	 *
	 * @access public
	 * @param  array  $key The key of field's array to create the HTML field.
	 * @param  string $out The HTML form output.
	 * @return string      Return the HTML Field Select with Options Group
	 */
	public function field_type_select_group( $key, $out = '' ) {

		$out .= $this->field_type_label( $key['name'], $key['_id'] ) . '<br/>';

		$out .= '<select id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" ';

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		if ( isset( $key['show_option_none'] ) ) {
			$none = ( is_string( $key['show_option_none'] ) ) ? $key['show_option_none'] : __( 'None', 'ItalyStrap' ) ;
			$key['options'] = array_merge( array( 'none' => $none ),$key['options'] );
		}

		foreach ( (array) $key['options'] as $group => $options ) {

			$out .= '<optgroup label="' . $group . '">';

			foreach ( $options as $field => $option ) {

				$out .= '<option value="' . esc_attr( $field ) . '" ';

				if ( esc_attr( $selected ) === $field ) {
					$out .= ' selected="selected" '; }

				$out .= '> ' . esc_html( $option ) . '</option>';
			}

			$out .= '</optgroup>';

		}

		$out .= '</select>';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->field_type_description( $key['desc'] ); }

		return $out;
	}

	/**
	 * Create the field description
	 *
	 * @access public
	 * @param  string $desc The description.
	 * @return string       Return the description
	 */
	public function field_type_description( $desc ) {

		return  '<br/><small class="description">' . esc_html( $desc ) . '</small>';

	}

	/**
	 * Create the field label
	 *
	 * @access public
	 * @param  string $name The labels name.
	 * @param  string $id   The labels ID.
	 * @return string       Return the labels
	 */
	public function field_type_label( $name = '', $id = '' ) {

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
