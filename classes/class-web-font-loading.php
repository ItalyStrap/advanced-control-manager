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
	 * [$var description]
	 *
	 * @var null
	 */
	private $var = null;

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( $argument = null ) {
		// Code...
	}

	/**
	 * Funzione per il lazyloading dei fonts
	 */
	function lazy_load_fonts() {

		$fonts = array(
			array(
			'family'	=> 'Open Sans',
			'variants'	=> array(
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic'
				),
			'subsets'	=> array(
				'latin-ext',
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'cyrillic-ext'
				),
			),
			array(
			'family'	=> 'Open Sans Condensed',
			'variants'	=> array(
				'300',
				'300italic',
				'regular',
				'italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic'
				),
			'subsets'	=> array(
				'latin-ext',
				'greek',
				'greek-ext',
				'latin',
				'vietnamese',
				'cyrillic',
				'cyrillic-ext'
				),
			),
			// array(
			// 'family'	=> 'Anton',
			// 'variants'	=> array(
			// 	'regular',
			// 	),
			// 'subsets'	=> array(
			// 	'latin-ext',
			// 	'greek',
			// 	'greek-ext',
			// 	'latin',
			// 	'vietnamese',
			// 	'cyrillic',
			// 	'cyrillic-ext'
			// 	),
			// ),
		);

		$fonts = apply_filters( 'italystrap-fonts-before-loading', $fonts, $this );

		$font_to_load = '';
		$check = '';
		$web_font_config = array();

		$range = range('a', 'z' );

		$count_fonts = count( $fonts ) - 1;

		$i = 1;
		foreach ( $fonts as $key => $font ) {

			$comma = ( $count_fonts >= $i ) ? ',' : '' ;

			$font_to_load .= $range[ $key + 1 ] . '=new a.FontFaceObserver("' . $font['family'] . '",{weight:' . $font['variants'][0] . '})' . $comma;
			$check = $range[ $key + 1 ] . '.check()' . $comma;

			$font['family'] = str_replace( ' ', '+', $font['family'] );

			$web_font_config['google']['families'][] = $font['family'] . ':' . $font['variants'][0] . ':' . $font['subsets'][3];

			$i++;
		}

		/**
		 * In caso di server password
		 * @link http://www.hashbangcode.com/blog/using-authentication-and-filegetcontents
		 * @var string
		 */
		// $username = 'pennamontata';
		// $password = 'pennamontata2015#';

		// $context = stream_context_create(array(
		// 	'http' => array(
		// 		'header'  => 'Authorization: Basic ' . base64_encode( "$username:$password" ),
		// 		),
		// 	)
		// );
		// $data = file_get_contents( ITALYSTRAP_PLUGIN_PATH . '/js/FontFaceObserver.min.php' , false, $context );
		// $FontFaceObserver = file_get_contents( ITALYSTRAP_PLUGIN_PATH . '/bower_components/fontfaceobserver/fontfaceobserver.js' );

		// d( $FontFaceObserver );

		// $fonts = file_get_contents( TEMPLATEPATH . '/admin/cache/google-web-fonts.txt' );
		// $fonts = json_decode( $fonts, true );

		// d( $fonts['items'] );

		// $data = file_get_contents( '../js/FontFaceObserver.min.php' , false, $context );

		// $script = '<script>' . $data . '</script>';

		$FontFaceObserver = '!function(){"use strict";function a(a){function b(){document.body?a():setTimeout(b,0)}b()}function b(a){this.a=document.createElement("div"),this.a.setAttribute("aria-hidden","true"),this.a.appendChild(document.createTextNode(a)),this.b=document.createElement("span"),this.c=document.createElement("span"),this.f=document.createElement("span"),this.e=document.createElement("span"),this.d=-1,this.b.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.c.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.e.style.cssText="display:inline-block;position:absolute;height:100%;width:100%;overflow:scroll;",this.f.style.cssText="display:inline-block;width:200%;height:200%;",this.b.appendChild(this.f),this.c.appendChild(this.e),this.a.appendChild(this.b),this.a.appendChild(this.c)}function c(a,b,c){a.a.style.cssText="min-width:20px;min-height:20px;display:inline-block;position:absolute;width:auto;margin:0;padding:0;top:-999px;left:-999px;white-space:nowrap;font-size:100px;font-family:"+b+";"+c}function d(a){var b=a.a.offsetWidth,c=b+100;return a.e.style.width=c+"px",a.c.scrollLeft=c,a.b.scrollLeft=a.b.scrollWidth+100,a.d!==b?(a.d=b,!0):!1}function e(a,b){a.b.addEventListener("scroll",function(){d(a)&&null!==a.a.parentNode&&b(a.d)},!1),a.c.addEventListener("scroll",function(){d(a)&&null!==a.a.parentNode&&b(a.d)},!1),d(a)}function f(a){this.a=i,this.b=void 0,this.c=[];var b=this;try{a(function(a){b.resolve(a)},function(a){b.reject(a)})}catch(c){b.reject(c)}}function g(a){setTimeout(function(){if(a.a!==i)for(;a.c.length;){var b=a.c.shift(),c=b[0],d=b[1],e=b[2],b=b[3];try{0===a.a?e("function"==typeof c?c.call(void 0,a.b):a.b):1===a.a&&("function"==typeof d?e(d.call(void 0,a.b)):b(a.b))}catch(f){b(f)}}},0)}function h(a,b){var c=b||{};this.family=a,this.style=c.style||"normal",this.variant=c.variant||"normal",this.weight=c.weight||"normal",this.stretch=c.stretch||"stretch",this.featureSettings=c.featureSettings||"normal"}var i=2;if(f.prototype.resolve=function(a){var b=this;if(b.a===i){if(a===b)throw new TypeError("Promise settled with itself.");var c=!1;try{var d=a&&a.then;if(null!==a&&"object"==typeof a&&"function"==typeof d)return void d.call(a,function(a){c||b.resolve(a),c=!0},function(a){c||b.reject(a),c=!0})}catch(e){return void(c||b.reject(e))}b.a=0,b.b=a,g(b)}},f.prototype.reject=function(a){if(this.a===i){if(a===this)throw new TypeError("Promise settled with itself.");this.a=1,this.b=a,g(this)}},f.prototype["catch"]=function(a){return this.then(void 0,a)},f.prototype.then=function(a,b){var c=this;return new f(function(d,e){c.c.push([a,b,d,e]),g(c)})},window.Promise){var j=window.Promise;j.prototype.then=window.Promise.prototype.then,j.prototype["catch"]=window.Promise.prototype["catch"],j.all=window.Promise.all,j.race=window.Promise.race,j.resolve=window.Promise.resolve,j.reject=window.Promise.reject}else j=f,j.prototype.then=f.prototype.then,j.prototype["catch"]=f.prototype["catch"],j.all=f.prototype.all,j.race=f.prototype.race,j.resolve=f.prototype.resolve,j.reject=f.prototype.reject;var k=null;h.prototype.a=function(d,f){var g=d||"BESbswy",h=f||3e3,i="font-style:"+this.style+";font-variant:"+this.variant+";font-weight:"+this.weight+";font-stretch:"+this.stretch+";font-feature-settings:"+this.featureSettings+";-moz-font-feature-settings:"+this.featureSettings+";-webkit-font-feature-settings:"+this.featureSettings+";",l=document.createElement("div"),m=new b(g),n=new b(g),o=new b(g),p=-1,q=-1,r=-1,s=-1,t=-1,u=-1,v=this;return new j(function(b,d){function f(){null!==l.parentNode&&l.parentNode.removeChild(l)}function g(){if((-1!==p&&-1!==q||-1!==p&&-1!==r||-1!==q&&-1!==r)&&(p===q||p===r||q===r)){if(null===k){var a=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent);k=!!a&&(536>parseInt(a[1],10)||536===parseInt(a[1],10)&&11>=parseInt(a[2],10))}k?p===s&&q===s&&r===s||p===t&&q===t&&r===t||p===u&&q===u&&r===u||(f(),b(v)):(f(),b(v))}}a(function(){function a(){if(Date.now()-b>=h)f(),d(v);else{var c=document.hidden;(!0===c||void 0===c)&&(p=m.a.offsetWidth,q=n.a.offsetWidth,r=o.a.offsetWidth,g()),setTimeout(a,50)}}var b=Date.now();c(m,"sans-serif",i),c(n,"serif",i),c(o,"monospace",i),l.appendChild(m.a),l.appendChild(n.a),l.appendChild(o.a),document.body.appendChild(l),s=m.a.offsetWidth,t=n.a.offsetWidth,u=o.a.offsetWidth,a(),e(m,function(a){p=a,g()}),c(m,v.family+",sans-serif",i),e(n,function(a){q=a,g()}),c(n,v.family+",serif",i),e(o,function(a){r=a,g()}),c(o,v.family+",monospace",i)})})},window.FontFaceObserver=h,window.FontFaceObserver.prototype.check=h.prototype.a}(),';

		$lazy_load_fonts = 'function(a){if(!(a.document.documentElement.className.indexOf("fonts-loaded")>-1)){var ' . $font_to_load . ';a.Promise.all([' . $check . ']).then(function(){a.document.documentElement.className+=" fonts-loaded"})}}(this),WebFontConfig=' . json_encode( $web_font_config ) . ',function(){var a=document.createElement("script");a.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js",a.type="text/javascript",a.async="true";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)}();';

		$script = '<script>' . $FontFaceObserver . $lazy_load_fonts . '</script>';

		echo $script;
	}
}
