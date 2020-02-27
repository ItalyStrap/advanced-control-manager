<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\TestCase\WPTestCase;
use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcher;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Lazyload\Image;
use ItalyStrap\Lazyload\ImageSubscriber;
use PHPUnit\Framework\Assert;
use Prophecy\Prophecy\ObjectProphecy;
use SplFileObject;
use WpunitTester;

class ImageSubscriberTest extends WPTestCase
{
    /**
     * @var WpunitTester
     */
    protected $tester;

	/**
	 * @var ObjectProphecy
	 */
	private $file;
	/**
	 * @var Config
	 */
	private $config;
	/**
	 * @var EventDispatcher
	 */
	private $dispatcher;
	/**
	 * @var ObjectProphecy
	 */
	private $image;

	/**
	 * @return Image
	 */
	public function getImage(): Image {
		return $this->image;
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
		return $this->dispatcher;
	}

	/**
	 * @return Config
	 */
	public function getConfig(): Config {
		return $this->config;
	}
    public function setUp(): void
    {
        // Before...
        parent::setUp();

		$this->config = new Config();
		$this->dispatcher = new EventDispatcher();
		$this->file = $this->prophesize( SplFileObject::class );
		$this->image = new Image($this->config, $this->dispatcher);

        // Your set up methods here.
    }

    public function tearDown(): void
    {
        // Your tear down methods here.

        // Then...
        parent::tearDown();
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
    public function instanceOk()
    {
        $sut = $this->getInstance();
    }

	/**
	 * @test
	 */
    public function makeSureItRuns()
    {
        $sut = $this->getInstance();
        $sut->onWpLoaded();
    }

	/**
	 * @test
	 */
    public function filteredContentHasLazyload()
    {
    	$this->dispatcher->addListener('italystrap_lazyload_image_events', function (array $events){
    		$events[] = [
				'custom_event',
				22
			];

    		return $events;
		});

        $sut = $this->getInstance();
        $sut->onWpLoaded();

        /** @var string $filtered */
        $filtered = $this->dispatcher->filter('custom_event', '<img src="screanshot.png" >');
        $expected = '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-lazy-src="screanshot.png" ><noscript><img src="screanshot.png" ></noscript><meta itemprop="image" content="screanshot.png"/>';

        Assert::assertStringMatchesFormat( $expected, $filtered, '' );
    }

	/**
	 * @test
	 */
    public function filterCustomString()
    {
		$sut = $this->getInstance();
		$sut->onWpLoaded();

		/** @var string $filtered */
		$filtered = $this->dispatcher->filter(
			'italystrap_lazyload_images_in_this_content',
			'<img src="screanshot.png" >'
		);
		$expected = '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-lazy-src="screanshot.png" ><noscript><img src="screanshot.png" ></noscript><meta itemprop="image" content="screanshot.png"/>';

		Assert::assertStringMatchesFormat( $expected, $filtered, '' );
    }


}
