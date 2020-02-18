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

namespace ItalyStrap\Lazyload;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Asset\Inline_Script;
use ItalyStrap\Asset\Inline_Style;

class Image implements Subscriber_Interface {

	const DEFAULT_PLACEHOLDER = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

	/**
	 * @var EventDispatcherInterface
	 */
	private $dispatcher;

	/**
	 * @var ConfigInterface
	 */
	private $config;

	/**
	 * @var \SplFileObject
	 */
	private $file;

	/**
	 * @return array
	 */
	public static function get_subscribed_events() {

		return [
			'wp_loaded'	=> 'onWpLoaded',
		];
	}

	/**
	 * Path for the unveil.js
	 *
	 * @var string
	 */
	private $script = '';

	/**
	 * The options of the plugin
	 *
	 * @var array
	 */
	private static $options;

	/**
	 * Init constructor
	 *
	 * @param ConfigInterface $config
	 * @param EventDispatcherInterface $dispatcher
	 * @param \SplFileObject $file
	 */
	public function __construct( ConfigInterface $config, EventDispatcherInterface $dispatcher, \SplFileInfo $file ) {

		$this->config = $config;
		$this->dispatcher = $dispatcher;
		$this->file = $file;

		/**
		 * Path for unveil.js
		 *
		 * @var string
		 */
		$this->script = $this->file->fread($this->file->getSize());
	}

	public function onWpLoaded(): void {

		if ( \is_admin() ) {
			return;
		}

		/**
		 * Experimental
		 * Da testare ed eventualmente mettere sotto opzione attivabile
		 * Funziona solo con priorità inferiore a 10 altrimenti
		 * le altre immagini non vengono elaborate
		 */
		$this->dispatcher->addListener( 'post_gallery', [$this, 'replaceSrcImageWithSrcPlaceholders'], 9 );

		if ( $this->config->get('lazyload_widget_text', false) ) {
			/**
			 * Experimental
			 * Da testare ed eventualmente mettere sotto opzione attivabile
			 */
			$this->dispatcher->addListener( 'widget_text', [$this, 'replaceSrcImageWithSrcPlaceholders'], 11 );
		}

		/**
		 * Run this later, so other content filters have run,
		 * including image_add_wh on WP.com
		 */
		$this->dispatcher->addListener( 'the_content', [$this, 'replaceSrcImageWithSrcPlaceholders'], 999 );

		$this->dispatcher->addListener( 'post_thumbnail_html', [$this, 'replaceSrcImageWithSrcPlaceholders'], 11 );
		$this->dispatcher->addListener( 'get_avatar', [$this, 'replaceSrcImageWithSrcPlaceholders'], 11 );

		/**
		 * Filter for custom header image in ItalyStrap theme
		 */
		$this->dispatcher->addListener( 'italystrap_custom_header_image', [$this, 'replaceSrcImageWithSrcPlaceholders'] );
		$this->dispatcher->addListener( 'italystrap_carousel_output', [$this, 'replaceSrcImageWithSrcPlaceholders'] );

		$events = [
			[
				'post_gallery',
				9
			],
			[
				'widget_text',
				11
			],
			[
				'the_content',
				PHP_INT_MAX
			],
			[
				'post_thumbnail_html',
				11
			],
			[
				'get_avatar',
				11
			],
			[
				'italystrap_custom_header_image'
			],
			[
				'italystrap_carousel_output'
			],
		];

//		\array_walk($events, function ( array $event, int $index ){
//			$this->dispatcher->addListener(
//				$event[0],
//				[$this, 'replaceSrcImageWithSrcPlaceholders'],
//				$event[1] ?? 10
//			);
//		});

		Inline_Script::set( $this->script() );
		Inline_Style::set( $this->style() );
	}

	/**
	 * Add image placeholders to image src
	 *
	 * @param string $content Content to be processed
	 * @return string
	 */
	public function replaceSrcImageWithSrcPlaceholders( string $content ) {

		if ( $this->isNotTheFrontEnd() ) {
			return $content;
		}

		if ( $this->hasAlreadyLazyLoaded( $content ) ) {
			return $content;
		}

		/**
		 * This is a pretty simple regex, but it works
		 *
		 * @var string
		 */
		return \preg_replace_callback(
			'#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#',
			function ( array $matches ) {
				return $this->replaceImg($matches);
			},
			$content
		);

	}

	/**
	 * @param array $matches
	 * @return string
	 */
	private function replaceImg( array $matches ) {

		/**
		 * Replace srcset, sizes and src attributes
		 */
		$content = \str_replace(
			['srcset', 'sizes'],
			['data-srcset', 'data-sizes'],
			\sprintf(
				'<img%1$ssrc="%2$s" data-src="%3$s"%4$s>',
				$matches[1],
				$this->getImagePlaceholder(),
				$matches[2],
				$matches[3]
			)
		);

		/**
		 * Add noscript fallback plus microdata
		 * The meta tag works only if there is Schema.org markup
		 */
		$content .= \sprintf(
			'<noscript><img%1$ssrc="%2$s"%3$s></noscript><meta itemprop="image" content="%2$s"/>',
			$matches[1],
			$matches[2],
			$matches[3]
		);

		return $content;
	}

	/**
	 * @link http://clubmate.fi/base64-encoded-1px-gifs-black-gray-and-transparent/
	 * Gif black
	 * data:image/gif;base64,R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=
	 * Gif grey
	 * data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==
	 * Gif transparent
	 * data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7
	 *
	 * @return string
	 */
	private function getImagePlaceholder() {
		return $this->dispatcher->filter( 'italystrap_lazy_load_placeholder_image',
			$this->config->get('lazyload-custom-placeholder', static::DEFAULT_PLACEHOLDER)
		);
	}

	/**
	 * @param $content
	 * @return string
	 */
	private function script(): string {

		/**
		 * Add script for img opacity
		 *
		 * @var string Js Code.
		 */
		// $output = 'jQuery(document).ready(function($){$("img").unveil(200, function(){$("img").load(function(){this.style.opacity = 1;});$(this).parent("img").css( "opacity","1");});});';

		//Da testare
		// $output = 'jQuery(document).ready(function($){$("img").unveil(200, function(){$(this).load(function(){$(this).css( "opacity","1");});});});';

		$this->script .= <<<SCRIPT
function force_load_img( img ) {
	var src = img.data("src");
	if (typeof src !== "undefined" && src !== ""){
		img.attr("src", src);
		img.data("src", "");
	}
}
jQuery(document).ready(function($){
	var img = $("img[data-src]");
	img.unveil(200, function(){
		img.load(function(){
			this.style.opacity = 1;
		});
	});
});
SCRIPT;

		return $this->script;

	}

	/**
	 * Add css to opacize img first are append to src
	 * This apply opacity 0 only for thoose images that have the data-src attributes
	 * normally added from this plugin.
	 *
	 * @return string Add opacity and transition to img
	 */
	private function style(): string {
		return 'img[data-src]{opacity:0;transition:opacity .3s ease-in;}';
	}

	/**
	 * @param string $content
	 * @return bool
	 */
	private function hasAlreadyLazyLoaded( string $content ): bool {
		return false !== \strpos( $content, 'data-src' );
	}

	/**
	 * @return bool
	 */
	private function isNotTheFrontEnd(): bool {
		return \is_feed() || \is_preview();
	}
}
