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
 *
 * @todo This class need more improvements
 */
class Share {

	/**
	 * [$var description]
	 *
	 * @var null
	 */
	private $social_url = array();

	private $options = array();

	/**
	 * [__construct description]
	 *
	 * @param [type] $argument [description].
	 */
	function __construct( array $options = array() ) {

		$this->options = $options;

		$wpseo_social = get_option( 'wpseo_social' );
		$this->via = ! empty( $option['twitter_site'] ) ? '&via=' . $option['twitter_site'] : '' ;

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999999 );
	}

	/**
	 * Enqueue scripts
	 *
	 * @param string $handle Script name
	 * @param string $src Script url
	 * @param array $deps (optional) Array of script names on which this script depends
	 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
	 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
	 */
	function enqueue_scripts() {
		wp_enqueue_style( 'fontello', ITALYSTRAP_PLUGIN_URL . 'css/social.css', null, null, null );
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
		$get_the_excerpt = get_the_content();
		$thumb_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( get_the_id() ) );

		$this->social_url = array(
			'facebook'	=> sprintf(
				'//www.facebook.com/sharer.php?u=%s&p[title]=%s',
				$get_permalink,
				$get_the_title
			),
			'twitter'	=> sprintf(
				'//twitter.com/share?url=%s&text=%s%s',
				$get_permalink,
				$get_the_title,
				$this->via
			),
			'gplus'		=> sprintf(
				'//plus.google.com/share?url=%s',
				$get_permalink
			),
			// 'linkedin'	=> sprintf(
			// 	'//www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
			// 	$get_permalink,
			// 	$get_the_title
			// ),
			'pinterest'	=> sprintf(
				'//pinterest.com/pin/create/button/?url=%s&media=%s&description=%s',
				$get_permalink,
				$thumb_url,
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

		$link_attr = array(
			'href'		=> '%1$s',
			'class'		=> 'btn btn-default btn-sm',
			'target'	=> 'popup',
			'onclick'	=> 'window.open("%1$s","popup","width=600,height=600"); return false;',
			'rel'		=> 'nofollow',
			'title'		=> __( 'Share on %2$s', 'italystrap' ),
		);

		foreach ( $this->social_url as $key => $url ) {

			$format = \ItalyStrap\Core\get_attr( $key, $link_attr );

			$output .= sprintf(
				'<li><a ' . $format . '><span class="font-icon icon-%2$s">%2$s</span></a></li>',
				$url,
				$key
			);
		}

		$output .= '</ul>';

		return $output;
	
	}

	/**
	 * Add social share
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function add_social_button( $content ) {

		if ( ! is_singular() ) {
			return $content;
		}

		$positions = array(
			'before'	=> '%2$s%1$s',
			'after'		=> '%1$s%2$s',
			'both'		=> '%2$s%1$s%2$s',
		);

		return sprintf(
			$positions[ $this->options['social_button_position'] ],
			$content,
			$this->get_social_button()
		);
	}
}
