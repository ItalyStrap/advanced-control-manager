<?php
/**
 * Facebook Page Widget API
 *
 * This class add a Facebook page with many settings.
 *
 * @see https://developers.facebook.com/docs/plugins/page-plugin/
 *
 * @link italystrap.com
 * @since 2.2.1
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core\Facebook;

use ItalyStrap\Core;

/**
 * Facebook Page
 */
class Page {

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
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function script() {
	
		return '(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/" + sfpp_script_vars.language + "/sdk.js#xfbml=1&version=v2.5&appId=" + sfpp_script_vars.appId;
			fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));';
	
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function script_2() {

		$data = array(
			'xfbml'		=> '1',
			'version'	=> 'v2.8',
			'appId'		=> '150302815027430',
		);
	
		echo sprintf(
			'<div id="fb-root"></div><script>(function(d, s, id){var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/%1$s/sdk.js#%2$s";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>',
		str_replace( '-', '_', get_bloginfo( 'language' ) ),
		http_build_query( $data )
		);
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function render( array $instance = array() ) {

		$instance = wp_parse_args( $instance, array(
			'href'					=> 'https://www.facebook.com/facebook',
			'width'					=> '380',
			'height'				=> '500',
			'tabs'					=> array(),
			'timeline_tab'			=> 'timeline',	// Da mettere in tabs
			'events_tab'			=> 'events',	// Da mettere in tabs
			'messages_tab'			=> 'messages',	// Da mettere in tabs
			'hide-cover'			=> 'false',
			'show_facepile'			=> 'true',
			'hide-cta'				=> 'false',
			'small_header'			=> 'false',
			'adapt_container_width'	=> 'true',
			'align'					=> 'none',
		) );
	
		$tab_output = array();

		if ( $instance['timeline_tab'] == 1 ) {
			array_push( $tab_output, 'timeline' );
		}
		if ( $instance['events_tab'] == 1 ) {
			array_push( $tab_output, 'events' );
		}
		if ( $instance['messages_tab'] == 1 ) {
			array_push( $tab_output, 'messages' );
		}

		$attr = array(
			'class'							=> 'fb-page',
			'data-href'						=> false !== strpos( $instance['href'], 'facebook.com' ) 
				? esc_attr( $instance['href'] )
				: '//facebook.com/' . esc_attr( $instance['href'] ),
			'data-width'					=> absint( $instance['width'] ),
			'data-height'					=> absint( $instance['height'] ),
			'data-tabs'						=> implode( ', ', $tab_output ),
			'data-hide-cover'				=> esc_attr( $instance['hide-cover'] ),
			'data-show-facepile'			=> esc_attr( $instance['show_facepile'] ),
			'data-hide-cta'					=> esc_attr( $instance['hide-cta'] ),
			'data-small-header'				=> esc_attr( $instance['small_header'] ),
			'data-adapt-container-width'	=> esc_attr( $instance['adapt_container_width'] ),
		);

		$output = sprintf(
			'<div%1$s><div%2$s></div></div>',
			' style="text-align:' . esc_attr( $instance['align'] ) . ';width: ' . absint( $instance['width'] ) . 'px;height: ' . absint( $instance['height'] ) . 'px;"',
			Core\get_attr( 'facebook_page', $attr, false, null )
		);

		// d( $output, str_replace( '-', '_', get_bloginfo( 'language' ) ) );

		return $output;
	}

	/**
	 * Function description
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function output() {
	
		echo $this->render( array() );
	
	}
}
