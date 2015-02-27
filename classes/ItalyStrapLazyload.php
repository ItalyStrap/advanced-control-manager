<?php
/**
 * Image lazy load forked from https://wordpress.org/plugins/lazy-load/
 * This function is used for lazy loading every image
 * It works with unveil.js (Not jquery.sonar)
 * @link http://luis-almeida.github.io/unveil/
 */
if ( ! class_exists( 'ItalyStrapLazyload' ) ){

	class ItalyStrapLazyload {

		private static $unveilpath = '';

		static function init() {

			if ( is_admin() )
				return;

			/**
			 * Experimental
			 * Da testare ed eventualmente mettere sotto opzione attivabile
			 * Funziona solo con priorità inferiore a 10 altrimenti
			 * le altre immagini non vengono elaborate
			 */
			add_filter( 'post_gallery', array( __CLASS__, 'add_image_placeholders' ), 9 );

			/**
			 * Run this later, so other content filters have run,
			 * including image_add_wh on WP.com
			 */
			add_filter( 'the_content', array( __CLASS__, 'add_image_placeholders' ), 999 );

			add_filter( 'post_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 11 );
			add_filter( 'get_avatar', array( __CLASS__, 'add_image_placeholders' ), 11 );

			/**
			 * Path for unveil.js
			 * @var string
			 */
			self::$unveilpath = ITALYSTRAP_PLUGIN_PATH . 'js/unveil.min.js';

			/**
			 * Append unveil.js content to globalsjs static variable
			 */
			ItalyStrapGlobals::set( self::italystrap_read_file( self::$unveilpath ) );


			/**
			 * Append css in static variable and print in front-end footer
			 */
			ItalyStrapGlobalsCss::set( self::custom_css() );
		}

		/**
		 * Add image placeholders to image src
		 * @param string $content Content to be processed
		 */
		static function add_image_placeholders( $content ) {

			/**
			 * Don't lazyload for feeds, previews
			 */
			if( is_feed() || is_preview() )
				return $content;

			/**
			 * Don't lazy-load if the content has already been run through previously
			 */
			if ( false !== strpos( $content, 'data-src' ) )
				return $content;

			/**
			 * In case you want to change the placeholder image use filter
			 * ItalyStrapLazyload_placeholder_image
			 * Gif link
			 * @link http://clubmate.fi/base64-encoded-1px-gifs-black-gray-and-transparent/
			 * Gif nera
			 * R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=
			 * Gif grigia
			 * R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==
			 * Gir trasparente
			 * R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7
			 * @var string
			 */
			$placeholder_image = apply_filters( 'ItalyStrapLazyload_placeholder_image', 'data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==' );

			/**
			 * This is a pretty simple regex, but it works
			 * The meta tag works only if there is Schema.org markup
			 * @var string
			 */
			$content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-src="${2}"${3}><meta  itemprop="url" content="${2}"/><noscript><img${1}src="${2}"${3}></noscript>', $placeholder_image ), $content );

			return $content;
		}

		/**
		 * Read and return unveil.js
		 * @link https://tommcfarlin.com/reading-files-with-php/
		 * @param  file $filename	The file for lazyloading
		 * @return string			[description]
		 */
		static function italystrap_read_file( $filename ) {

			// Check to see if the file exists at the specified path
			if ( ! file_exists( $filename ) ) {
			throw new Exception( "The file doesn't exist." );
			}
			// Open the file for reading
			$file_resource = fopen( $filename, 'r' );
			/* Read the entire contents of the file which is indicated by
			* the filesize argument
			*/
			$content = fread( $file_resource, filesize( $filename ) );
			fclose( $file_resource );

			/**
			 * Add script for img opacity
			 * @var string Js Code
			 */
			$output = 'jQuery(document).ready(function($){$("img").unveil(200, function(){$("img").load(function(){this.style.opacity = 1;});$(this).parent("img").css( "opacity","1");});});';

			$content .= $content . $output;

			return $content;

		}

		/**
		 * Add css to opacize img first are append to src
		 * @return string Add opacity and transition to img
		 */
		static function custom_css(){

			$custom_css = 'img{opacity:0;transition:opacity .3s ease-in;}';

			return $custom_css;

		}

	}

	/**
	 * Funtion for apply lazyload for custom content
	 * @param string $content Custom content
	 */
	function ItalyStrapLazyload_add_placeholders( $content ) {
		return ItalyStrapLazyload::add_image_placeholders( $content );
	}

}