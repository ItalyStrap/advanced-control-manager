<?php
/**
 * Renders extra controls for image dimension in the new media UI.
 *
 * @since 1.1.0
 * @package ItalyStrap
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
if ( ! class_exists( 'ItalyStrapAdminMediaSettings' ) ) {

	class ItalyStrapAdminMediaSettings extends ItalyStrap\Image\Size {

		public function __construct() {
			_deprecated_function( __CLASS__, '2.5', 'ItalyStrap\\\Image\\\Size' );
		}

		/**
		 * Add list of all image size to administrators in the WordPress Media Library
		 *
		 * @package ItalyStrap
		 *
		 * @link http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
		 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/image_size_names_choose
		 * @link http://www.deluxeblogtips.com/2011/06/list-registered-image-sizes.html
		 * @link https://wordpress.org/support/topic/image_size_names_choose-not-working
		 *
		 * @param  array $args Default WordPres image list ('thumbnail', 'medium', 'large').
		 * @return array       New list with custom and standard thumb
		 */
		function get_image_sizes( array $args = array() ) {

			global $_wp_additional_image_sizes;

			/**
			 * An array of each size value
			 *
			 * @var array
			 */
			$sizes = array();

			/**
			 * An array of name of each thumb, custom and standard
			 *
			 * @var array
			 */
			$get_intermediate_image_sizes = get_intermediate_image_sizes();

			/**
			 * Create the full array with sizes and crop info
			 */
			foreach ( $get_intermediate_image_sizes as $_size ) {

				/**
				 * The name of each thumb
				 * var $_size string
				 */
				if ( $_size && in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {

					/**
					 * Get the size of each standard thumb
					 */
					$sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
					$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
	 				$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

				} elseif ( $_size && isset( $_wp_additional_image_sizes[ $_size ] ) ) {

					/**
					 * Get the size of each custom thumb
					 */
					$sizes[ $_size ] = array(
						'width'		=> $_wp_additional_image_sizes[ $_size ]['width'],
						'height'	=> $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'		=> $_wp_additional_image_sizes[ $_size ]['crop'],
					);

				}

				/**
				 * Add thumb name to administrators in the WordPress Media Library
				 */
				if ( isset( $sizes[ $_size ] ) ) {

					// $custom[ $_size ] = ucwords( str_replace( '-', ' ', $_size ) ) . ' ' . $sizes[ $_size ]['width'] . 'x' . $sizes[ $_size ]['height'];
					$custom[ $_size ] = sprintf(
						'%s %sx%spx',
						ucwords( str_replace( '-', ' ', $_size ) ),
						$sizes[ $_size ]['width'],
						$sizes[ $_size ]['height']
					);
				}
			}

			return array_merge( $args, $custom );
		}
	}

}
