<?php
/**
 * Post Meta Class
 *
 * Get the post meta and apply some functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

/**
 * The Post Meta Class
 */
class Post_Meta {

	/**
	 * CMB prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * CMB _prefix
	 *
	 * @var string
	 */
	private $_prefix;

	/**
	 * Init the constructor
	 */
	function __construct() {

		/**
		 * Start with an underscore to hide fields from custom fields list
		 *
		 * @var string
		 */
		$this->prefix = 'italystrap';

		$this->_prefix = '_' . $this->prefix;

	}

	/**
	 * Riceve il valore della metabox top o middle
	 * Se in pagina usa get_post_meta invece se in categoria usa get_term_meta
	 *
	 * @param  int     $post_id ID del post o della categoria.
	 * @param  string  $key     ID della metabox.
	 * @param  boolean $single  Se ritornare un array o un valore.
	 * @param  boolean $is_cat  Boleano per detterminare se la funzione è richiamata in categoria o meno.
	 * @return string      Contenuto della metabox
	 */
	public function get_metabox( $post_id, $key = '', $single = false, $is_cat = false ) {

		$content = '';

		if ( function_exists( 'get_term_meta' ) && $is_cat ) {
			$content = get_term_meta( $post_id, $key, $single );
		} else {
			$content = get_post_meta( $post_id, $key, $single );
		}
		return $content;

	}

	/**
	 * Uso questa funzione per fare l'escape,
	 * stampare shortcode e inserire i paragrafi
	 * per la funzione controzzi_get_the_metabox_page_content()
	 *
	 * @param  string $content Contenuto della meta.
	 * @param  bool   $wpautop      Use wpautop.
	 * @param  bool   $kses         Use wp_kses_post.
	 * @param  bool   $do_shortcode Use do_shortcode.
	 *
	 * @return string          Ritorna il contenuto formattato e pulito.
	 */
	public function escape_metabox( $content = '', $wpautop = true, $kses = true, $do_shortcode = true ) {

		if ( $wpautop ) {
			$content = wpautop( $content );
		}
		if ( $kses ) {
			$content = wp_kses_post( $content );
		}
		if ( $do_shortcode ) {
			$content = do_shortcode( $content );
		}

		return $content;

	}

	/**
	 * Add custom script to the global style or script
	 */
	public function add_post_type_custom_script() {

		$style = $this->get_metabox( get_the_id(), $this->_prefix . '_custom_css_settings', true );
		$js = $this->get_metabox( get_the_id(), $this->_prefix . '_custom_js_settings', true );

		ItalyStrapGlobalsCss::set( $style );
		ItalyStrapGlobals::set( $js );

	}

	/**
	 * Get classes
	 *
	 * @param  string $filter_name The name of the filter.
	 * @param  array  $classes The array with body classes.
	 *
	 * @return array               The new array
	 */
	public function get_classes( $filter_name, array $classes ) {

		$class_name = $this->get_metabox( get_the_id(), $this->_prefix . '_custom_' . $filter_name .'_settings', true );

		$class_name = explode( ' ', $class_name );

		foreach ( $class_name as $key => $value ) {
			$classes[] = $value;
		}
		return $classes;

	}

	/**
	 * Add body class to the body_class filter
	 *
	 * @param  array $classes The array with body classes.
	 *
	 * @return array          The new array
	 */
	function body_class( $classes ) {
		return $this->get_classes( 'body_classes', $classes );
	}

	/**
	 * Add post class to the post_class filter
	 *
	 * @param  array $classes The array with post classes.
	 *
	 * @return array          The new array
	 */
	function post_class( $classes ) {
		return $this->get_classes( 'post_classes', $classes );
	}
}
