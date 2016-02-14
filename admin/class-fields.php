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

/**
 * Class for make field type
 */
class Fields {

	// function __construct() {
	// }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		if ( isset( $key['rows'] ) ) {
			$out .= 'rows="' . esc_attr( $key['rows'] ) . '" '; }

		if ( isset( $key['cols'] ) ) {
			$out .= 'cols="' . esc_attr( $key['cols'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="'. esc_attr( $key['_id'] ) .'" name="' . esc_attr( $key['_name'] ) . '">'.esc_html( $value );

		$out .= '</textarea>';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		$out .= $this->create_field_label( $key['name'], $key['_id'] );

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr__( $field ) . '" ';

			if ( $selected === $field ) {
				$out .= ' selected="selected" '; }

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= 'size="6" multiple> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $field => $option ) {

			$out .= '<option value="' . esc_attr__( $field ) . '" ';

			if ( $selected === $field ) {
				$out .= ' selected="selected" '; }

			$out .= '> ' . esc_html( $option ) . '</option>';

		}

		$out .= ' </select> ';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$out .= '> ';

		$selected = isset( $key['value'] ) ? $key['value'] : $key['default'];

		foreach ( $key['options'] as $group => $options ) {

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
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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

		if ( isset( $key['class'] ) ) {
			$out .= 'class="' . esc_attr( $key['class'] ) . '" '; }

		$value = isset( $key['value'] ) ? $key['value'] : $key['default'];

		$out .= 'id="' . esc_attr( $key['_id'] ) . '" name="' . esc_attr( $key['_name'] ) . '" value="' . esc_attr__( $value ) . '" ';

		if ( isset( $key['size'] ) ) {
			$out .= 'size="' . esc_attr( $key['size'] ) . '" '; }

		$out .= ' />';

		if ( isset( $key['desc'] ) ) {
			$out .= $this->create_field_description( $key['desc'] ); }

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
