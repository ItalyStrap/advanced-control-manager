<?php
/**
 * Class API for Social Sharing Button
 *
 * @since 2.1.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core\Social;

/**
 * The vCard Class
 */
class Share {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $social_url = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options = array() ) {

		$wpseo_social = get_option( 'wpseo_social' );
		$this->via = ! empty( $option['twitter_site'] ) ? '&via=' . $option['twitter_site'] : '' ;
	}

	/**
	 * Set social url
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function set_social_url() {

		$get_permalink = get_permalink();
		$get_the_title = get_the_title();
	
		$this->social_url = array(
			'facebook'	=> sprintf(
				'http://www.facebook.com/sharer.php?u=%s',
				$get_permalink
			),
			'twitter'	=> sprintf(
				'https://twitter.com/share?url=%s&text=%s%s',
				$get_permalink,
				$get_the_title,
				$this->via
			),
			'gplus'		=> sprintf(
				'https://plus.google.com/share?url=%s',
				$get_permalink
			),
			'linkedin'	=> sprintf(
				'http://www.linkedin.com/shareArticle?url=%s&title=%s',
				$get_permalink,
				$get_the_title
			),
		);
	
	}

	/**
	 * Render Sharing button
	 *
	 * Alcune idee https://www.google.it/search?site=&source=hp&q=twitter+url+share&oq=twitter+url+share&gs_l=hp.3.0.0i19l3j0i22i10i30i19j0i22i30i19l6.1898.11624.0.13381.18.18.0.0.0.0.2152.4618.0j13j0j1j1j9-1.16.0....0...1c.1.64.hp..2.15.4160.0.93FpzCXhTCY
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function get_social_button() {

		$this->set_social_url();

		$output = '<ul class="social-button list-inline">';
	
		foreach ( $this->social_url as $key => $url ) {

			$output .= sprintf(
				'<li><a href="%s" target="popup" onclick="%s return false;" rel="nofollow" title="%s"><span class="font-icon icon-facebook">%s</span></a></li>',
				$url,
				sprintf(
					'window.open("%s","popup","width=600,height=600");',
					$url
				),
				sprintf(
					__( 'Share on %s', 'italystrap' ),
					$key
				),
				$key
			);
		}

		$output .= '</ul>';

		return $output;
	
	}
}
