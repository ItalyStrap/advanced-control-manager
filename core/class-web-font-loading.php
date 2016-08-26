<?php
/**
 * Abstract class for Web Font Loading
 *
 * This class is used to load Webfont async
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Web Font Loading class
 */
class Web_Font_loading {

	/**
	 * Google API font URL
	 *
	 * @var string
	 */
	private $google_api_url = '';

	/**
	 * Google API Key
	 *
	 * @var string
	 */
	private $google_api_key = '';

	/**
	 * Init the class.
	 *
	 * @param array $options The plugin options.
	 */
	function __construct( array $options = array() ) {

		$this->options = $options;

		$this->google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts';

		$this->google_api_key = isset( $options['google_api_key'] ) ? '?key=' . esc_attr( $options['google_api_key'] ) : '';

		$this->fonts = $this->get_remote_fonts();

		$this->get_theme_mods = get_theme_mods();

	}

	/**
	 * Get the google fonts from the API or in the cache
	 *
	 * @return String
	 */
	public function get_remote_fonts() {

		if ( empty( $this->options['google_api_key'] ) ) {
			return array();
		}

		// delete_transient( 'italystrap_google_fonts' );

		if ( false === ( $fonts = get_transient( 'italystrap_google_fonts' ) ) ) {

			$font_content = wp_remote_get( $this->google_api_url . $this->google_api_key, array( 'sslverify' => false ) );

			$fonts = wp_remote_retrieve_body( $font_content );

			$fonts = json_decode( $fonts );

			set_transient( 'italystrap_google_fonts', $fonts, MONTH_IN_SECONDS );

		}

		return $fonts->items;

	}

	/**
	 * Prepare fonts
	 *
	 * @return array        Retunr the array with all fonts
	 */
	public function prepare_fonts() {

		$get_theme_mods = $this->get_theme_mods;
		// remove_theme_mod( 'body_font_family' );
		// remove_theme_mod( 'body_font_variants' );
		// remove_theme_mod( 'body_font_subsets' );
		// remove_theme_mod( 'heading_font_family' );
		// remove_theme_mod( 'heading_font_variants' );
		// remove_theme_mod( 'heading_font_subsets' );
		// d( $get_theme_mods );
		// d( $get_theme_mods['body_font_family'] );
		// d( $get_theme_mods['body_font_variants'] );
		// d( $get_theme_mods['body_font_subsets'] );
		// d( $get_theme_mods['heading_font_family'] );
		// d( $get_theme_mods['heading_font_variants'] );
		// d( $get_theme_mods['heading_font_subsets'] );
		$template_part = array(
			'first',
			'second',
		);

		$fonts = array();

		foreach ( $template_part as $key => $part ) {

			if ( empty( $this->fonts ) ) {
				continue;
			}

			if ( ! isset( $get_theme_mods[ $part . '_font_family' ] ) ) {
				continue;
			}

			if ( ! isset( $get_theme_mods[ $part . '_font_variants' ] ) ) {
				$get_theme_mods[ $part . '_font_variants' ] = '';
			}

			if ( ! isset( $get_theme_mods[ $part . '_font_subsets' ] ) ) {
				$get_theme_mods[ $part . '_font_subsets' ] = 'latin';
			}

			/**
			 * The array position of the font
			 *
			 * @var int The position of the font because it is an array.
			 */
			$position = (int) $get_theme_mods[ $part . '_font_family' ];

			/**
			 * Get the font family from $position
			 *
			 * @var string The font family name.
			 */
			$fonts[ $key ]['family'] = $this->fonts[ $position ]->family;

			$fonts[ $key ]['variants'] = array_intersect(
				explode( ',', $get_theme_mods[ $part . '_font_variants' ] ),
				$this->fonts[ $position ]->variants
				);

			$fonts[ $key ]['subsets'] = array_intersect(
				explode( ',', $get_theme_mods[ $part . '_font_subsets' ] ),
				$this->fonts[ $position ]->subsets
				);
		}

		/**
		 * This must be an array
		 */
		return (array) apply_filters( 'italystrap_fonts_before_loading', $fonts, $this );

	}

	/**
	 * Funzione per il lazyloading dei fonts
	 */
	function lazy_load_fonts() {

		$fonts = $this->prepare_fonts();

		if ( empty( $fonts ) ) {
			return;
		}

		$font_to_observe = '';
		$check = '';
		$web_font_config = array();

		$range = range( 'a', 'z' );

		$count_fonts = count( $fonts ) - 1;

		$i = 1;
		foreach ( $fonts as $key => $font ) {

			$comma = ( $count_fonts >= $i ) ? ',' : '' ;

			/**
			 * The weight of the font
			 *
			 * @todo Per il momento la variabile è vuota, valutare se utilizzarla
			 *       ma in quel caso si dovrà fare un foreach per ogni
			 *       weight assegnato al font.
			 * $weight = strpos( $font['variants'], 'italic' ) ? '' : ',{weight:' . absint( $font['variants'] ) . '}';
			 *
			 * @var string
			 */
			$weight = '';

			$font_to_observe .= $range[ $key + 1 ] . '=new a.FontFaceObserver("' . esc_attr( $font['family'] ) . '"' . $weight . ')' . $comma;

			$check = $range[ $key + 1 ] . '.check()' . $comma;

			$font['family'] = str_replace( ' ', '+', $font['family'] );

			$web_font_config['google']['families'][] = esc_attr( $font['family'] ) . ':' . esc_attr( implode( ',', $font['variants'] ) ) . ':' . esc_attr( implode( ',', $font['subsets'] ) );

			$i++;
		}

		$css = '.fonts-loaded body{font-family: "' . esc_attr( $fonts[0]['family'] ) . '";}.fonts-loaded h1,.fonts-loaded h2,.fonts-loaded h3,.fonts-loaded h4,.fonts-loaded h5,.fonts-loaded h6 {font-family: "' . esc_attr( $fonts[0]['family'] ) . '";}';

		echo '<style>' . $css . '</style>'; // XSS ok.

		$font_face_observer = '!function(){"use strict";function a(a){function b(){document.body?a():setTimeout(b,0)}b()}function b(a){this.a=document.createElement("div"),this.a.setAttribute("aria-hidden","true"),this.a.appendChild(document.createTextNode(a)),this.b=document.createElement("span"),this.c=document.createElement("span"),this.f=document.createElement("span"),this.e=document.createElement("span"),this.d=-1,this.b.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.c.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.e.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.f.style.cssText="display:inline-block;width:200%;height:200%;",this.b.appendChild(this.f),this.c.appendChild(this.e),this.a.appendChild(this.b),this.a.appendChild(this.c)}function c(a,b,c){a.a.style.cssText="min-width:20px;min-height:20px;display:inline-block;position:absolute;width:auto;margin:0;padding:0;top:-999px;left:-999px;white-space:nowrap;font-size:100px;font-family:"+b+";"+c}function d(a){var b=a.a.offsetWidth,c=b+100;return a.e.style.width=c+"px",a.c.scrollLeft=c,a.b.scrollLeft=a.b.scrollWidth+100,a.d!==b?(a.d=b,!0):!1}function e(a,b){a.b.addEventListener("scroll",function(){d(a)&&null!==a.a.parentNode&&b(a.d)},!1),a.c.addEventListener("scroll",function(){d(a)&&null!==a.a.parentNode&&b(a.d)},!1),d(a)}function f(a){this.a=i,this.b=void 0,this.c=[];var b=this;try{a(function(a){b.resolve(a)},function(a){b.reject(a)})}catch(c){b.reject(c)}}function g(a){setTimeout(function(){if(a.a!==i)for(;a.c.length;){var b=a.c.shift(),c=b[0],d=b[1],e=b[2],b=b[3];try{0===a.a?e("function"==typeof c?c.call(void 0,a.b):a.b):1===a.a&&("function"==typeof d?e(d.call(void 0,a.b)):b(a.b))}catch(f){b(f)}}},0)}function h(a,b){var c=b||{};this.family=a,this.style=c.style||"normal",this.variant=c.variant||"normal",this.weight=c.weight||"normal",this.stretch=c.stretch||"stretch",this.featureSettings=c.featureSettings||"normal"}var i=2;if(f.prototype.resolve=function(a){var b=this;if(b.a===i){if(a===b)throw new TypeError("Promise settled with itself.");var c=!1;try{var d=a&&a.then;if(null!==a&&"object"==typeof a&&"function"==typeof d)return void d.call(a,function(a){c||b.resolve(a),c=!0},function(a){c||b.reject(a),c=!0})}catch(e){return void(c||b.reject(e))}b.a=0,b.b=a,g(b)}},f.prototype.reject=function(a){if(this.a===i){if(a===this)throw new TypeError("Promise settled with itself.");this.a=1,this.b=a,g(this)}},f.prototype["catch"]=function(a){return this.then(void 0,a)},f.prototype.then=function(a,b){var c=this;return new f(function(d,e){c.c.push([a,b,d,e]),g(c)})},window.Promise){var j=window.Promise;j.prototype.then=window.Promise.prototype.then,j.prototype["catch"]=window.Promise.prototype["catch"],j.all=window.Promise.all,j.race=window.Promise.race,j.resolve=window.Promise.resolve,j.reject=window.Promise.reject}else j=f,j.prototype.then=f.prototype.then,j.prototype["catch"]=f.prototype["catch"],j.all=f.prototype.all,j.race=f.prototype.race,j.resolve=f.prototype.resolve,j.reject=f.prototype.reject;var k=null;h.prototype.a=function(d,f){var g=d||"BESbswy",h=f||3e3,i="font-style:"+this.style+";font-variant:"+this.variant+";font-weight:"+this.weight+";font-stretch:"+this.stretch+";font-feature-settings:"+this.featureSettings+";-moz-font-feature-settings:"+this.featureSettings+";-webkit-font-feature-settings:"+this.featureSettings+";",l=document.createElement("div"),m=new b(g),n=new b(g),o=new b(g),p=-1,q=-1,r=-1,s=-1,t=-1,u=-1,v=this;return new j(function(b,d){function f(){null!==l.parentNode&&l.parentNode.removeChild(l)}function g(){if((-1!==p&&-1!==q||-1!==p&&-1!==r||-1!==q&&-1!==r)&&(p===q||p===r||q===r)){if(null===k){var a=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent);k=!!a&&(536>parseInt(a[1],10)||536===parseInt(a[1],10)&&11>=parseInt(a[2],10))}k?p===s&&q===s&&r===s||p===t&&q===t&&r===t||p===u&&q===u&&r===u||(f(),b(v)):(f(),b(v))}}a(function(){function a(){if(Date.now()-b>=h)f(),d(v);else{var c=document.hidden;(!0===c||void 0===c)&&(p=m.a.offsetWidth,q=n.a.offsetWidth,r=o.a.offsetWidth,g()),setTimeout(a,50)}}var b=Date.now();c(m,"sans-serif",i),c(n,"serif",i),c(o,"monospace",i),l.appendChild(m.a),l.appendChild(n.a),l.appendChild(o.a),document.body.appendChild(l),s=m.a.offsetWidth,t=n.a.offsetWidth,u=o.a.offsetWidth,a(),e(m,function(a){p=a,g()}),c(m,v.family+",sans-serif",i),e(n,function(a){q=a,g()}),c(n,v.family+",serif",i),e(o,function(a){r=a,g()}),c(o,v.family+",monospace",i)})})},window.FontFaceObserver=h,window.FontFaceObserver.prototype.check=h.prototype.a}(),';

		$lazy_load_fonts = 'function(a){if(!(a.document.documentElement.className.indexOf("fonts-loaded")>-1)){var ' . $font_to_observe . ';a.Promise.all([' . $check . ']).then(function(){a.document.documentElement.className+=" fonts-loaded"})}}(this),WebFontConfig=' . json_encode( $web_font_config ) . ',function(){var a=document.createElement("script");a.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js",a.type="text/javascript",a.async="true";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)}();';

		$script = '<script>' . $font_face_observer . $lazy_load_fonts . '</script>';

		echo $script; // XSS ok.
	}
}
