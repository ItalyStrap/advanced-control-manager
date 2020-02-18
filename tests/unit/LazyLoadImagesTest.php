<?php

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Event\EventDispatcher;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Lazyload\Image;
use Prophecy\Argument;
use tad\FunctionMockerLe;

class LazyLoadImagesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 * @var \Prophecy\Prophecy\ObjectProphecy
	 */
	private $config;

	/**
	 * @var \Prophecy\Prophecy\ObjectProphecy
	 */
	private $dispatcher;
	/**
	 * @var \Prophecy\Prophecy\ObjectProphecy
	 */
	private $file;

	/**
	 * @return \SplFileObject
	 */
	public function getFile(): \SplFileObject {
		return $this->file->reveal();
	}

	/**
	 * @return EventDispatcherInterface
	 */
	public function getDispatcher(): EventDispatcherInterface {
		return $this->dispatcher->reveal();
	}

	/**
	 * @return ConfigInterface
	 */
	public function getConfig(): ConfigInterface {
		return $this->config->reveal();
	}

	private $is_admin = false;
	private $is_feed = false;
	private $is_preview = false;

	protected function _before()
    {

    	if ( ! defined('ITALYSTRAP_PLUGIN_PATH') ) {
			define( 'ITALYSTRAP_PLUGIN_PATH', '' );
		}

    	FunctionMockerLe\undefineAll(['is_admin','is_feed','is_preview']);
    	FunctionMockerLe\define('is_admin', function (): bool {return $this->is_admin;});
    	FunctionMockerLe\define('is_feed', function (): bool {return $this->is_feed;});
    	FunctionMockerLe\define('is_preview', function (): bool {return $this->is_preview;});

    	$this->config = $this->prophesize( ConfigInterface::class );
    	$this->dispatcher = $this->prophesize( EventDispatcher::class );
    	$this->file = $this->prophesize( \SplFileObject::class );
    }

    protected function _after()
    {
    }

	private function getInstance() {
		$sut = new Image($this->getConfig(), $this->getDispatcher(), $this->getFile());
		$this->assertInstanceOf( Image::class, $sut, '' );
		return $sut;
    }

	/**
	 * @test
	 */
	public function itShouldBeInstantiable() {
		$sut = $this->getInstance();
    }

	/**
	 * @test
	 */
	public function itShouldExecuteInit() {

		$this->config->get(Argument::any(), false)->willReturn(false);

		$this->dispatcher->addListener(
			Argument::type('string'),
			Argument::type('callable'),
			Argument::any(),
			Argument::any()
		)->will(function ($args): bool {
			return true;
		})->shouldBeCalled();

		$this->dispatcher->filter('italystrap_lazyload_image_events', Argument::any())->will(function ($args){
			return $args[1];
		})->shouldBeCalled();

		$sut = $this->getInstance();
		$sut->onWpLoaded();
    }

	public function imageProvider() {
		return [
			'simple image'	=> [
				'<img src="some/image/uri">',
<<<'IMG'
<img src="PLACEHOLDER" data-src="some/image/uri"><noscript><img src="some/image/uri"></noscript><meta itemprop="image" content="some/image/uri"/>
IMG
			],
		];
	}

	/**
	 * @test
	 * @dataProvider imageProvider()
	 */
	public function itShouldReplaceImageWith( $image, $expected ) {

		$this->dispatcher->filter(Argument::type('string'), Argument::any())->willReturn('PLACEHOLDER');

		$sut = $this->getInstance();
		$replaced = $sut->replaceSrcImageWithSrcPlaceholders($image);
		$this->assertStringContainsString( $replaced, $expected, '' );
    }

	public function frontProvider() {
		return [
			'is feed'	=> [
				true,
				false,
			],
			'is preview'	=> [
				false,
				true,
			],
			'is feed and preview'	=> [
				true,
				true,
			],
		];
    }

	/**
	 * @test
	 * @dataProvider frontProvider()
	 */
	public function itShouldReturnContentIsIsAdminTrue( $feed, $preview ) {

		$this->is_feed = $feed;
		$this->is_preview = $preview;

		$content = '<img src="some/image/uri">';

		$sut = $this->getInstance();
		$replaced = $sut->replaceSrcImageWithSrcPlaceholders($content);
		$this->assertSame($content, $replaced, '');
    }

	/**
	 * @test
	 */
	public function itShouldReturnContentIfHasAlreadyLazyLoaded() {

		$content = '<img src="some/image/uri" data-src="some/image/uri">';

		$sut = $this->getInstance();
		$replaced = $sut->replaceSrcImageWithSrcPlaceholders($content);
		$this->assertSame($content, $replaced, '');
    }
}
