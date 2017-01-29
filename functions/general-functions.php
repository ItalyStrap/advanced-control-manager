<?php
/**
 * General functions for the plugin
 *
 * @package ItalyStrap
 * @since   2.0.0
 */

namespace ItalyStrap\Core;

use \ItalyStrapAdminMediaSettings;

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 2.0.0
 *
 * @param array  $pairs     Entire list of supported attributes and their defaults.
 * @param array  $atts      User defined attributes in shortcode tag.
 * @param string $shortcode Optional. The name of the shortcode, provided for context to enable filtering.
 * @return array Combined and filtered attribute list.
 */
function shortcode_atts_multidimensional_array( array $pairs, array $atts, $shortcode = '' ) {

	$atts = (array) $atts;

	$array = array();

	foreach ( $pairs as $name => $default ) {

		if ( array_key_exists( $name, $atts ) ) {
			$array[ $name ] = $atts[ $name ]; } else {
			$array[ $name ] = ( ( ! empty( $default['default'] ) ) ? $default['default'] : '' ); }
	}

	/**
	 * Filter a shortcode's default attributes.
	 *
	 * If the third parameter of the shortcode_atts() function is present then this filter is available.
	 * The third parameter, $shortcode, is the name of the shortcode.
	 *
	 * @param array  $array       The output array of shortcode attributes.
	 * @param array  $pairs     The supported attributes and their defaults.
	 * @param array  $atts      The user defined shortcode attributes.
	 * @param string $shortcode The shortcode name.
	 */
	if ( $shortcode ) {
		$array = apply_filters( "shortcode_atts_multidimensional_array_{$shortcode}", $array, $pairs, $atts, $shortcode );
	}

	return $array;

}

/**
 * Get options default
 *
 * @param  array $config The configuration array.
 *
 * @return array         Return the array with options default.
 */
function get_default_from_config( array $config = array() ) {

	$options_default = array();

	foreach ( $config as $value ) {
		foreach ( $value['settings_fields'] as $settings_fields_value ) {
			$default = isset( $settings_fields_value['args']['default'] ) ? $settings_fields_value['args']['default'] : null;
			$options_default[ $settings_fields_value['id'] ] = $default;
		}
	}

	return $options_default;

}

/**
 * Read and return file content
 *
 * @link https://tommcfarlin.com/reading-files-with-php/
 *
 * @param  file $filename	The file for lazyloading.
 *
 * @throws Exception If the file doesn't exist.
 *
 * @return string $content	Return the content of the file
 */
function get_file_content( $filename ) {

	// Check to see if the file exists at the specified path.
	if ( ! file_exists( $filename ) ) {
		throw new Exception( __( 'The file doesn\'t exist.', 'italystrap' ) );
	}

	// Open the file for reading.
	$file_resource = fopen( $filename, 'r' );

	/**
	 * Read the entire contents of the file which is indicated by
	 * the filesize argument
	 */
	$content = fread( $file_resource, filesize( $filename ) );
	fclose( $file_resource );

	return $content;

}

/**
 * Get an array with the taxonomies list
 *
 * @param  string $tax The name of taxonomy type.
 * @return array       Return an array with tax list
 */
function get_taxonomies_list_array( $tax ) {

	/**
	 * Array of taxonomies
	 *
	 * @todo Make object cache, see https://10up.github.io/Engineering-Best-Practices/php/#performance
	 * @todo Add a default value
	 * @var array
	 */
	$tax_arrays = get_terms( $tax );

	$get_taxonomies_list_array = array();

	if ( $tax_arrays && ! is_wp_error( $tax_arrays ) ) {

		foreach ( $tax_arrays as $tax_array ) {

			$get_taxonomies_list_array[ $tax_array->term_id ] = $tax_array->name;

		}
	}

	return $get_taxonomies_list_array;
}

/**
 * Get the size media registered
 *
 * @param  array $custom_size Custom size.
 * @return array               Return the array with all media size plus custom size
 */
function get_image_size_array( $custom_size = array() ) {

	/**
	 * Instance of list of image sizes
	 *
	 * @var ItalyStrapAdminMediaSettings
	 */
	$image_size_media = new ItalyStrapAdminMediaSettings;

	$image_size_media_array = (array) $image_size_media->get_image_sizes( array( 'full' => __( 'Real size', 'italystrap' ) ) );

	return $image_size_media_array;

}

/**
 * Convert open square and closed square in < > and
 * allow you to put some HTML tag in title, widget and post.
 *
 * Function from HTML Widget Text plugins
 *
 * @author Jaimyn Mayer https://jabelone.com.au
 *
 * @package ItalyStrap
 * @since 2.0.0
 * @param  string $title The title.
 * @return string        The title converted
 */
function render_html_in_title_output( $title ) {

	$tagopen = '{{'; // Our HTML opening tag replacement.
	$tagclose = '}}'; // Our HTML closing tag replacement.

	$title = str_replace( $tagopen, '<', $title );
	$title = str_replace( $tagclose, '>', $title );

	return $title;

}

/**
 * Return the instance of Mobile Detect
 * Use this function with apply_filter and then add_filter
 * Example:
 * Add this snippet where you want use the object
 * $detect = '';
 * $detect = apply_filters( 'mobile_detect', $detect );
 *
 * Then use add_filter to append object
 * add_filter( 'mobile_detect', 'ItalyStrap\Core\new_mobile_detect' );
 *
 * @see class-carousel.php for more information
 * @param  null $mobile_detect An empty variable.
 * @return object              Instance of Mobiloe detect
 */
function new_mobile_detect( $mobile_detect ) {

	$mobile_detect = new \Detection\MobileDetect;
	/**
	 * Istantiate Mobile_Detect class for responive use
	 *
	 * @todo Passare l'istanza dentro la classe http://stackoverflow.com/a/10634148
	 * @var obj
	 */
	return $mobile_detect;

}

/**
 * Kill the emojis
 *
 * @author Ryan Hellyer
 * @link https://geek.hellyer.kiwi/
 *
 * @since 2.0.0
 * @version 1.5.1 (Version of the original plugin)
 */
function kill_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'kill_emojis_tinymce' );
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @author Ryan Hellyer
 * @link https://geek.hellyer.kiwi/
 *
 * @since 2.0.0
 * @version 1.5.1 (Version of the original plugin)
 * 
 * @param    array  $plugins  
 * @return   array           Difference betwen the two arrays
 */
function kill_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * Register widget
 * Test function from LucaTume
 * @todo Make some test.
 *
 * @param  object $widget [description].
 */
// function register_widget( $widget ) {
// 	global $wp_widget_factory;

// 	$wp_widget_factory->widgets[ get_class( $widget ) ] = $widget;
// }

/**
 * Remove widget title
 *
 * @hooked 'widget_title' - 999
 *
 * @author Stephen Cronin <http://www.scratch99.com/>
 *
 * @param  string      $widget_title The widget title.
 *
 * @return string/null               The widget title or null.
 */
function remove_widget_title( $widget_title ) {

	if ( substr ( $widget_title, 0, 2 ) === '!!' ) {
		return;
	}
	
	return $widget_title;
}

if ( ! function_exists( 'ItalyStrap\Core\get_attr' ) ) {

	/**
	 * Build list of attributes into a string and apply contextual filter on string.
	 *
	 * The contextual filter is of the form `italystrap_attr_{context}_output`.
	 *
	 * @since 4.0.0
	 *
	 * @see In general-function on the plugin.
	 *
	 * @param  string $context    The context, to build filter name.
	 * @param  array  $attributes Optional. Extra attributes to merge with defaults.
	 * @param  bool   $echo       True for echoing or false for returning the value.
	 *                            Default false.
	 * @param  null   $args       Optional. Extra arguments in case is needed.
	 *
	 * @return string String of HTML attributes and values.
	 */
	function get_attr( $context, array $attr = array(), $echo = false, $args = null ) {

		$html = '';

		/**
		 * This filters the array with html attributes.
		 *
		 * @var array
		 */
		$attr = (array) apply_filters( "italystrap_{$context}_attr", $attr, $context, $args );

		foreach ( $attr as $key => $value ) {

			if ( empty( $value ) ) {
				continue;
			}

			if ( true === $value ) {

				$html .= esc_html( $key ) . ' ';
			} else {

				$html .= sprintf(
					' %s="%s"',
					esc_html( $key ),
					( 'href' === $key ) ? esc_url( $value ) : esc_attr( $value )
				);
			}
		}

		/**
		 * This filters the output of the html attributes. 
		 *
		 * @var string
		 */
		$html = apply_filters( "italystrap_attr_{$context}_output", $html, $attr, $context, $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}
}

/**
 * Is Beta version
 *
 * @return bool Return true if ITALYSTRAP_BETA version is declared
 */
function is_beta() {

	return (bool) defined( 'ITALYSTRAP_BETA' );
}

/**
 * Is Beta version
 *
 * @return bool Return true if ITALYSTRAP_BETA version is declared
 */
function is_debug() {

	return (bool) defined( 'WP_DEBUG' ) && WP_DEBUG ;
}
/**
 * Get the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function get_breadcrumbs( array $args = array() ) {

	$breadcrumbs = new Breadcrumbs();

	if ( ! is_object( $breadcrumbs ) ) {
		return;
	}

	return $breadcrumbs->get_the_breadcrumbs( $args );
}

/**
 * Print the Breadcrumbs
 *
 * @param  array  $args The breadcrumbs arguments.
 *                      @see class Breadcrumbs for more info.
 * @return string       Return the breadcrumbs html.
 */
function breadcrumbs( array $args = array() ) {

	echo get_breadcrumbs( $args );
}

/**
 * Do breadcrumbs
 *
 * @since 2.2.0
 *
 * @param  array  $args The breadcrumbs arguments.
 */
function do_breadcrumbs( array $args = array() ) {

	breadcrumbs( $args );
}
add_action( 'do_breadcrumbs', __NAMESPACE__ . '\do_breadcrumbs' );

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * @uses locate_template( $template_names );
 *
 * This allow to override the template used in plugin.
 *
 * @since 2.0.0
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @return string The template filename if one is located.
 */
function get_template( $template_names ) {

	$located = '';

	$located = locate_template( $template_names );

	if ( '' === $located ) {
		return ITALYSTRAP_PLUGIN_PATH . $template_names;
	}

	return $located;
}


/**
 * Return img tag lazyloaded
 * @param  string $content Text content to be processed
 * @return string          Text content processed
 */
function get_apply_lazyload( $content ) {

	return Lazy_Load_Image::add_image_placeholders( $content );

}

/**
 * Echo img tag lazyloaded
 * @param  string $content Text content to be processed
 * @return string          Text content processed
 */
function apply_lazyload( $content ) {

	echo Lazy_Load_Image::add_image_placeholders( $content );

}

/**
 * Retrieves the attachment ID from the file URL
 *
 * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param  string $image_url The src of the image.
 *
 * @return int               Return the ID of the image
 */
function get_image_id_from_url( $image_url ) {

	$attachment = wp_cache_get( 'get_image_id' . $image_url );

	if ( false === $attachment ) {

		global $wpdb;
		$attachment = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID
				FROM $wpdb->posts
				WHERE guid='%s';",
				$image_url
			)
		);
		wp_cache_set( 'get_image_id' . $image_url, $attachment );
	}

	$attachment[0] = isset( $attachment[0] ) ? $attachment[0] : null;

	return absint( $attachment[0] );
}

/**
 * Cambio il testo per il link al post successivo
 *
 * @param  array $args Argomenti per le funzioni di paginazione.
 * @return array       Array aggiornato
 */
// function change_next_article_link( $args ) {

// 	$args['previous_link'] = '<i class="glyphicon glyphicon-arrow-left"></i> Precedente</li>';
// 	$args['next_link'] = 'Successivo <i class="glyphicon glyphicon-arrow-right"></i></li></ul>';
// 	return $args;
// }
// add_filter( 'italystrap_previous_next_post_link_args', __NAMESPACE__ . '\change_next_article_link' );

/**
 * This shortcode is only for intarnal use
 *
 * @param  array $atts Shortcode attributes.
 *
 * @return string      The shortcode output
 */
function _display_config( $atts ) {

	if ( ! isset( $atts['file'] ) ) {
		return;
	}

	if ( ! file_exists( ITALYSTRAP_PLUGIN_PATH . 'config/' . $atts['file'] . '.php' ) ) {
		return __( 'No file founded', 'italystrap' );
	}

	$get_settings = require( ITALYSTRAP_PLUGIN_PATH . 'config/' . $atts['file'] . '.php' );

	$output = '<ul class="list-unstyled">';
	$output .= sprintf(
		'<h3>%s</h3>',
		__( 'Options', 'italystrap' )
	);

	foreach ( (array) $get_settings as $key => $setting ) {
		$output .= sprintf(
			'<li><h4>%s</h4><p>Attribute: <code>%s</code><br>Default: <code>%s</code><br>%s</p></li>',
			esc_attr( $setting['name'] ),
			esc_attr( $setting['id'] ),
			esc_attr( $setting['default'] ),
			wp_kses_post( $setting['desc'] )
		);
	}

	$output .= '</ul>';

	return $output;
}

add_shortcode( 'display_config', __NAMESPACE__ . '\_display_config' );

/**
 * This shortcode is only for intarnal use
 *
 * @param  array $atts Shortcode attributes.
 *
 * @return string      The shortcode output
 */
function _display_example( $atts ) {

	if ( ! isset( $atts['file'] ) ) {
		return;
	}

	if ( ! file_exists( ITALYSTRAP_PLUGIN_PATH . $atts['file'] . '.md' ) ) {
		return __( 'No file founded', 'italystrap' );
	}

	static $parsedown = null;

	if ( ! $parsedown ) {
		$parsedown = new \Parsedown();
	}

	$get_example = \ItalyStrap\Core\get_file_content( ITALYSTRAP_PLUGIN_PATH . $atts['file'] . '.md' );

	return $parsedown->text( $get_example );
}

add_shortcode( 'display_example', __NAMESPACE__ . '\_display_example' );

/**
 * ItalyStrap plugin on activation
 */
function plugin_on_activation() {

	// var_dump( get_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2', true ) );
	// var_dump( delete_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2' ) );

	add_action( 'admin_notices', __NAMESPACE__ . '\_admin_notice_success', 999 );

}

/**
 * Add notice.
 * Internal use.
 */
function _admin_notice_success() {

	global $pagenow;
	if ( 'plugins.php' !== $pagenow ) {
		return null;
	}

	if ( get_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2' )  ) {
		return null;
	}

	$message = 'Welcome to the new version 2.0 of ItalyStrap plugin, this is a major release and it is a breaking change, this means that the prevous version of the code has been changed, for example the function for the breadcrumbs, the lazyload and the carousel, please read the <a href="admin.php?page=italystrap-dashboard">changelog</a> for more information.';
	?>
	<div class="notice notice-success is-dismissible">
		<p><?php echo $message; ?></p>
		<a class="dismiss-notice" href="?italystrap_notice_plugin_update=0">Hide Notice</a>
		<!-- <button type="button" class="notice-dismiss"> -->
		<!-- <span class="screen-reader-text">Dismiss this notice.</span> -->
	<!-- </button> -->
	</div>
	<?php
}

/**
 * Add the dismiss notice.
 */
function _notice_plugin_update() {

	if ( ! isset( $_GET['italystrap_notice_plugin_update'] ) ) {
		return null;
	}

	if ( '0' !== $_GET['italystrap_notice_plugin_update'] ) {
		return null;
	}

	add_user_meta( get_current_user_id(), 'italystrap_notice_plugin_update_2', 'true', true );

	/**
	 * Maybe this is not the right way to handle this but for now it works.
	 * Because otherways fire everytime.
	 */
	wp_redirect( 'plugins.php', 301 );
	exit;
}
