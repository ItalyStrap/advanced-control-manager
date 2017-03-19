<?php
/**
 * Image lazy load forked from https://wordpress.org/plugins/lazy-load/
 * This function is used for lazy loading every image
 * It works with unveil.js (Not jquery.sonar)
 * @link http://luis-almeida.github.io/unveil/
 *
 * @todo Verificare perché stampa gli script anche se
 *       non ci sono immagini da mettere in lazyload
 */

namespace ItalyStrap\Core\Lazyload;

use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;

/**
 *
 */
class Lazy_Load_Image {

	/**
	 * Path for the unveil.js
	 *
	 * @var string
	 */
	private static $unveilpath = '';

	/**
	 * The options of the plugin
	 *
	 * @var array
	 */
	private static $options;

	/**
	 * Init constructor
	 *
	 * @param  array $options The plugin options.
	 */
	public function __construct( array $options ) {
		_deprecated_function( __CLASS__, '2.5', 'ItalyStrap\\\Lazy_Load\\\Image' );
		self::$options = $options;

		$file = WP_DEBUG ? 'js/src/unveil.js' : 'js/unveil.min.js';

		/**
		 * Path for unveil.js
		 *
		 * @var string
		 */
		self::$unveilpath = ITALYSTRAP_PLUGIN_PATH . $file;
	}

	/**
	 * Init Lazy_Load
	 */
	static function init() {

		if ( is_admin() ) {
			return;
		}

		/**
		 * Experimental
		 * Da testare ed eventualmente mettere sotto opzione attivabile
		 * Funziona solo con priorità inferiore a 10 altrimenti
		 * le altre immagini non vengono elaborate
		 */
		add_filter( 'post_gallery', array( __CLASS__, 'add_image_placeholders' ), 9 );

		if ( ! empty( $options['lazyload_widget_text'] ) ) {
			/**
			 * Experimental
			 * Da testare ed eventualmente mettere sotto opzione attivabile
			 */
			add_filter( 'widget_text', array( __CLASS__, 'add_image_placeholders' ), 11 );
		}

		/**
		 * Run this later, so other content filters have run,
		 * including image_add_wh on WP.com
		 */
		add_filter( 'the_content', array( __CLASS__, 'add_image_placeholders' ), 999 );

		add_filter( 'post_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 11 );
		add_filter( 'get_avatar', array( __CLASS__, 'add_image_placeholders' ), 11 );

		/**
		 * Filter for custom header image in Italystrap theme
		 */
		add_filter( 'italystrap_custom_header_image', array( __CLASS__, 'add_image_placeholders' ) );

		/**
		 * Append unveil.js content to globalsjs static variable
		 */
		Inline_Script::set( self::get_unveil( self::$unveilpath ) );


		/**
		 * Append css in static variable and print in front-end footer
		 */
		Inline_Style::set( self::custom_css() );
	}

	/**
	 * Add image placeholders to image src
	 *
	 * @param string $content Content to be processed
	 */
	static function add_image_placeholders( $content ) {

		/**
		 * Don't lazyload for feeds, previews
		 */
		if ( is_feed() || is_preview() ) {
			return $content;
		}


		/**
		 * Don't lazy-load if the content has already been run through previously
		 */
		if ( false !== strpos( $content, 'data-src' ) ) {
			return $content;
		}

		/**
		 * This is a pretty simple regex, but it works
		 *
		 * @var string
		 */
		return preg_replace_callback(
			'#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#',
			array( __CLASS__ , 'replace_callback' ),
			$content
		);

	}


	/**
	 * Replace callback
	 *
	 * @param  array $matches The matches value from content.
	 * @return string         The content 
	 */
	/**
	 * [replace_callback description]
	 *
	 * @param  array $matches [description]
	 *
	 * @return string          [description]
	 */
	static function replace_callback( $matches ) {

		$placeholder_image = self::get_placeholder();

		$content = '';

		/**
		 * Replace srcset, sizes and src attributes
		 */
		$content = str_replace(
			array( 'srcset', 'sizes' ),
			array( 'data-srcset', 'data-sizes' ),
			sprintf(
				'<img%1$ssrc="%2$s" data-src="%3$s"%4$s>',
				$matches[1],
				$placeholder_image,
				$matches[2],
				$matches[3]
			)
		);

		/**
		 * Add noscript fallback plus microdata
		 * The meta tag works only if there is Schema.org markup
		 */
		$content .= sprintf(
			'<noscript><img%1$ssrc="%2$s"%3$s></noscript><meta itemprop="image" content="%2$s"/>',
			$matches[1],
			$matches[2],
			$matches[3]
		);

		return $content;
	}

	/**
	 * Get the placeholder
	 *
	 * @return string        THe placeholder
	 */
	private static function get_placeholder() {

		/**
		 * In case you want to change the placeholder image use filter
		 * ItalyStrapLazyload_placeholder_image
		 * Gif link
		 *
		 * @link http://clubmate.fi/base64-encoded-1px-gifs-black-gray-and-transparent/
		 * Gif nera
		 * data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=
		 * Gif grigia
		 * data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==
		 * Gif trasparente
		 * data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7
		 * @var string
		 */
		$placeholder_image = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

		if ( ! empty( self::$options['lazyload-custom-placeholder'] ) ) {
			$placeholder_image = self::$options['lazyload-custom-placeholder'];
		}

		return apply_filters( 'italystrap_lazy_load_placeholder_image', $placeholder_image );
	}

	/**
	 * Read and return unveil.js
	 *
	 * @link https://tommcfarlin.com/reading-files-with-php/
	 * @param  file $filename	The file for lazyloading.
	 * @return string			[description]
	 */
	static function get_unveil( $filename ) {

		$content = '';

		$content = \ItalyStrap\Core\get_file_content( $filename );

		/**
		 * Add script for img opacity
		 *
		 * @var string Js Code.
		 */
		// $output = 'jQuery(document).ready(function($){$("img").unveil(200, function(){$("img").load(function(){this.style.opacity = 1;});$(this).parent("img").css( "opacity","1");});});';

		//Da testare
		// $output = 'jQuery(document).ready(function($){$("img").unveil(200, function(){$(this).load(function(){$(this).css( "opacity","1");});});});';

		$content .= 'jQuery(document).ready(function($){$("img").unveil(200, function(){$("img").load(function(){this.style.opacity = 1;});});});';

		return $content;

	}

	/**
	 * Add css to opacize img first are append to src
	 *
	 * @return string Add opacity and transition to img
	 */
	static function custom_css() {

		$custom_css = 'img{opacity:0;transition:opacity .3s ease-in;}';

		/**
		 * return $custom_css;
		 */
		return null;

	}
}
