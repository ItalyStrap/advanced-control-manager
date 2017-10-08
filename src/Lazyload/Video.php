<?php
/**
 * LazyLoad Video API 
 *
 * Class for lazyloading video embedded
 * @link https://webdesign.tutsplus.com/tutorials/how-to-lazy-load-embedded-youtube-videos--cms-26743
 * @link https://github.com/viktorbergehall/lazyframe/blob/master/src/lazyframe.js
 *
 * @link http://italystrap.com
 * @since 2.2.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Lazyload;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;
use ItalyStrap\Asset\Minify;

/**
 * Class description
 */
class Video implements Subscriber_Interface {

	/**
	 * Returns an array of hooks that this subscriber wants to register with
	 * the WordPress plugin API.
	 *
	 * @hooked embed_oembed_html - 10
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {

		return array(
			// 'hook_name'							=> 'method_name',
			/**
			 * This run on [embed] shortcode
			 */
			'embed_oembed_html'	=> array(
				'function_to_add'	=> 'get_embed',
				'accepted_args'		=> 4,
			),
			'oembed_result'	=> array(
				'function_to_add'	=> 'get_embed',
				'accepted_args'		=> 3,
			),
			'wp_video_shortcode'	=> array(
				'function_to_add'	=> 'get_video_shortcode',
				'accepted_args'		=> 5,
			),
			/**
			 * @todo Future improvements
			 */
			// 'embed_defaults'	=> array(
			// 	'function_to_add'	=> 'embed_defaults',
			// 	'accepted_args'		=> 2,
			// ),
		);
	}

	/**
	 * [$regex description]
	 *
	 * @see WP_oEmbed::$providers in wp-includes/class-oembed.php for more regex
	 * @see wp_video_shortcode() in wp-includes/media.php L#2428
	 *
	 * @regex null
	 */
	// private $regex = '#https?://(www.)?youtube\.com/(?:v|embed)/([^/]+)#i';
	private $regex = '#(?:v=|\/v\/|\.be\/)([a-zA-Z0-9_-]+)#i';

	private $minify = null;

	/**
	 * [__construct description]
	 *
	 * @param Minify $minify Minify object.
	 */
	function __construct( Minify $minify ) {
		$this->minify = $minify;
		Inline_Style::set( $this->get_css() );
		Inline_Script::set( $this->get_js() );
	}

	/**
	 * Filters the default array of embed dimensions.
	 *
	 * @since 2.8.0
	 *
	 * @see wp-includes/embed.php
	 *
	 * @param array  $size An array of embed width and height values
	 *                     in pixels (in that order).
	 * @param string $url  The URL that should be embedded.
	 */
	public function embed_defaults( $size, $url ) {
		return $size;
	}

	/**
	 * Get embed
	 *
	 * @todo Problema con gli embed da link di siti WordPress, controllare $matches[1]
	 *       Provare con questa url nell'editor https://css-tricks.com/moving-to-https-on-wordpress/
	 *
	 * @param  string $value [description]
	 *
	 * @return string        [description]
	 */
	public function get_embed( $cache, $url, $attr, $post_ID = null ) {

		return $this->get_video_html( $url, $cache );
	}

	/**
	 * Get embed
	 *
	 * @todo Problema con gli embed da link di siti WordPress, controllare $matches[1]
	 *       Provare con questa url nell'editor https://css-tricks.com/moving-to-https-on-wordpress/
	 *
	 * @param  string $output  Video shortcode HTML output.
	 * @param  array  $atts    Array of video shortcode attributes.
	 * @param  string $video   Video file.
	 * @param  int    $post_id Post ID.
	 * @param  string $library Media library used for the video shortcode.
	 *
	 * @return string          The video HTML output
	 */
	public function get_video_shortcode( $html, $atts, $video, $post_id, $library ) {

		if ( ! isset( $atts['src'] ) ) {
			return $html;
		}

		return $this->get_video_html( $atts['src'], $html );
	}

	/**
	 * Get the video HTML
	 *
	 * @param  string $url The url of the video.
	 *
	 * @return string      Return the video HTML for lazy load
	 */
	public function get_video_html( $url, $html ) {

		preg_match( $this->regex, $url, $matches );
		
		if ( ! isset( $matches[1] ) ) {
			return $html;
		}

		$html = sprintf(
			'<div class="lazyload-video-wrap"><div class="lazyload-video youtube" data-embed="%s"><div class="play-button"></div></div></div>',
			$matches[1]
		);

		return $html;
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_css() {
	
		$css = '.youtube {
		    background-color: #000;
		    margin-bottom: 30px;
		    position: relative;
		    padding-top: 56.25%;
		    overflow: hidden;
		    cursor: pointer;
		}
		.youtube img {
		    width: 100%;
		    top: -16.82%;
		    left: 0;
		    opacity: 0.7;
		}
		.youtube .play-button {
		    width: 90px;
		    height: 60px;
		    background-color: #333;
		    box-shadow: 0 0 30px rgba( 0,0,0,0.6 );
		    z-index: 1;
		    opacity: 0.8;
		    border-radius: 6px;
		}
		.youtube .play-button:before {
		    content: "";
		    border-style: solid;
		    border-width: 15px 0 15px 26.0px;
		    border-color: transparent transparent transparent #fff;
		}
		.youtube img,
		.youtube .play-button {
		    cursor: pointer;
		}
		.youtube img,
		.youtube iframe,
		.youtube .play-button,
		.youtube .play-button:before {
		    position: absolute;
		}
		.youtube .play-button,
		.youtube .play-button:before {
		    top: 50%;
		    left: 50%;
		    transform: translate3d( -50%, -50%, 0 );
		}
		.youtube iframe {
		    height: 100%;
		    width: 100%;
		    top: 0;
		    left: 0;
		}';

		return $this->minify->run( $css );
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_js() {
	
		$js = '( function() {

		    var youtube = document.querySelectorAll( ".youtube" );
		    
		    for (var i = 0; i < youtube.length; i++) {
		        
		        var source = "https://img.youtube.com/vi/"+ youtube[i].dataset.embed +"/sddefault.jpg";
		        
		        var image = new Image();
		                image.src = source;
		                image.addEventListener( "load", function() {
		                    youtube[ i ].appendChild( image );
		                }( i ) );
		        
		                youtube[i].addEventListener( "click", function() {

		                    var iframe = document.createElement( "iframe" );

		                            iframe.setAttribute( "frameborder", "0" );
		                            iframe.setAttribute( "allowfullscreen", "" );
		                            iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );

		                            this.innerHTML = "";
		                            this.appendChild( iframe );
		                } );    
		    };
		    
		} )();';

		return $this->minify->run( $js );
	}
}
