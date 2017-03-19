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

use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;

/**
 * Class description
 */
class Video {

	/**
	 * [$regex description]
	 *
	 * @see WP_oEmbed::$providers in wp-includes/class-oembed.php for more regex
	 *
	 * @regex null
	 */
	// private $regex = '#https?://(www.)?youtube\.com/(?:v|embed)/([^/]+)#i';
	private $regex = '#(?:v=|\/v\/|\.be\/)([a-zA-Z0-9_-]+)#i';

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( $argument = null ) {
		add_filter( 'embed_oembed_html', array( $this, 'get_embed' ), 10, 4 );
		Inline_Style::set( $this->get_css() );
		Inline_Script::set( $this->get_js() );
	}

	/**
	 * Get embed
	 *
	 * @todo Problema con gli embed da link di siti WordPress, controllare $matches[1]
	 *       Provare con questa url nell'editor https://css-tricks.com/moving-to-https-on-wordpress/
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_embed( $cache, $url, $attr, $post_ID ) {
		// d( $url );
		preg_match( $this->regex, $url, $matches );
	// d( $matches );

		$html = sprintf(
			'<div class="youtube" data-embed="%s"><div class="play-button"></div></div>',
			$matches[1]
		);

		return $html;
		return $cache;
	
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_css() {
	
		return '.youtube {
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
	
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_js() {
	
		return '( function() {

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
	
	}
}
