<?php
/**
 * Image lazy load
 * This function is used for lazy loading every image
 * For work it must have unveil.js plugin in js/src
 * @link http://luis-almeida.github.io/unveil/
 *
 * Add enqueue script in script.php if is necessary (Io ho inserito in gruntfile.js)
 * Add file in gruntfile.js uglify and then "grunt uglify"
 *
 * Than put jquery code below in the custom.js file
 * $("img").unveil(200, function() {
 *		$('.img-responsive').load(function() {
 *			this.style.opacity = 1;
 *		});
 *		$(this).parent('.img-responsive').css( "opacity", "1" );
 *	});
 */
if ( ! class_exists( 'ItalyStrapLazyload' ) ){

	class ItalyStrapLazyload {

		private static $unveilpath = '';

		public function _costruct(){

		}

		static function init() {

			if ( is_admin() )
				return;

			self::$unveilpath = ITALYSTRAP_PLUGIN_PATH . 'js/unveil.min.js';

			ItalyStrapGlobals::set( self::acme_read_file( self::$unveilpath ) );

			// add_filter( 'post_gallery', array( __CLASS__, 'add_image_placeholders' ), 11 );
			
			/**
			 * run this later, so other content filters have run, including image_add_wh on WP.com
			 */
			add_filter( 'the_content', array( __CLASS__, 'add_image_placeholders' ), 999 );

			add_filter( 'post_thumbnail_html', array( __CLASS__, 'add_image_placeholders' ), 11 );
			add_filter( 'get_avatar', array( __CLASS__, 'add_image_placeholders' ), 11 );

			/**
			 * Append javascript in static variable and print in front-end footer
			 */
			ItalyStrapGlobalsCss::set( self::custom_css() );
		}

		static function add_image_placeholders( $content ) {
			// Don't lazyload for feeds, previews, mobile
			if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) )
				return $content;

			// Don't lazy-load if the content has already been run through previously
			if ( false !== strpos( $content, 'data-src' ) )
				return $content;

			// In case you want to change the placeholder image
			// $placeholder_image = apply_filters( 'ItalyStrapLazyload_placeholder_image', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' );

			// Gif grigia
			// @link http://clubmate.fi/base64-encoded-1px-gifs-black-gray-and-transparent/
			$placeholder_image = apply_filters( 'ItalyStrapLazyload_placeholder_image', 'data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==' );

			// This is a pretty simple regex, but it works
			$content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-src="${2}"${3}><noscript><img${1}src="${2}"${3}></noscript>', $placeholder_image ), $content );

			return $content;
		}

		/**
		 * [acme_read_file description]
		 * @link https://tommcfarlin.com/reading-files-with-php/
		 * @param  [type] $filename [description]
		 * @return [type]           [description]
		 */
		static function acme_read_file( $filename ) {

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

			$output = 'jQuery(document).ready(function($){$("img").unveil(200, function() {
			$(\'.img-responsive\').load(function() { this.style.opacity = 1; });
			$(this).parent(\'.img-responsive\').css( "opacity", "1" );});});';

			$content .= $content . $output;

			return $content;

		}

		static function custom_css(){

			$custom_css = '.img-responsive {opacity: 0;transition: opacity .3s ease-in;}';


			// wp_add_inline_style( 'bootstrap', $custom_css );
			return $custom_css;
		}

	}

	function ItalyStrapLazyload_add_placeholders( $content ) {
		return ItalyStrapLazyload::add_image_placeholders( $content );
	}

}