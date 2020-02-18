<?php

class LazyLoadTest extends \Codeception\TestCase\WPTestCase {

	public function setUp(): void  {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown(): void  {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Test if a Lazy Load class works
	 */
	public function test_lazy_load_image_works() {

		$img = '<img width="640" height="480" sizes="(max-width: 640px) 100vw, 640px" srcset="http://192.168.1.10/italystrap/wp-content/uploads/2013/09/dsc20050604_133440_34211-300x225.jpg 300w, http://192.168.1.10/italystrap/wp-content/uploads/2013/09/dsc20050604_133440_34211.jpg 640w" style="max-height:480px" itemprop="image" alt="dsc20050604_133440_34211" class="center-block img-responsive attachment-full size-full" src="http://192.168.1.10/italystrap/wp-content/uploads/2013/09/dsc20050604_133440_34211.jpg">';

		$content = ItalyStrap\Lazyload\Image::replaceSrcImageWithSrcPlaceholders( $img );

		$this->assertTrue( false !== strpos( $content, 'data-src' ) );
	}

	/**
	 * Test if unveil.min exist
	 */
	public function test_unveil_exist() {

		$unveilpath = ITALYSTRAP_PLUGIN_PATH . 'js/unveil.min.js';

		$this->assertTrue( file_exists( $unveilpath ) );
	}
}
