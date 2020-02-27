<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Lazyload\Image;
use ItalyStrap\Lazyload\ImageSubscriber;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use SplFileObject;
use tad\FunctionMockerLe;

class ImageSubscriberTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 * @var ObjectProphecy
	 */
	private $config;

	/**
	 * @var ObjectProphecy
	 */
	private $dispatcher;
	/**
	 * @var ObjectProphecy
	 */
	private $file;
	/**
	 * @var ObjectProphecy
	 */
	private $image;

	/**
	 * @return Image
	 */
	public function getImage(): Image {
		return $this->image->reveal();
	}

	/**
	 * @return SplFileObject
	 */
	public function getFile(): SplFileObject {
		return $this->file->reveal();
	}

	/**
	 * @return EventDispatcherInterface
	 */
	public function getDispatcher(): EventDispatcherInterface {
		return $this->dispatcher->reveal();
	}

	/**
	 * @return Config
	 */
	public function getConfig(): Config {
		return $this->config->reveal();
	}

	private $is_admin = false;
	private $is_feed = false;
	private $is_preview = false;

	protected function _before()
    {
    	FunctionMockerLe\define('is_admin', function (): bool {return $this->is_admin;});
    	FunctionMockerLe\define('is_feed', function (): bool {return $this->is_feed;});
    	FunctionMockerLe\define('is_preview', function (): bool {return $this->is_preview;});

    	$this->config = $this->prophesize( Config::class );
    	$this->dispatcher = $this->prophesize( EventDispatcherInterface::class );
    	$this->file = $this->prophesize( SplFileObject::class );
    	$this->image = $this->prophesize( Image::class );
    }

    protected function _after()
    {
    	FunctionMockerLe\undefineAll(['is_admin','is_feed','is_preview']);
    }

	private function getInstance() {
		$sut = new ImageSubscriber(
			$this->getConfig(),
			$this->getDispatcher(),
			$this->getFile(),
			$this->getImage()
		);
		$this->assertInstanceOf( ImageSubscriber::class, $sut, '' );
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
}
