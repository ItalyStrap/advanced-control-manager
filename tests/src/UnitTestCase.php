<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Lazyload\Image;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use SplFileObject;
use tad\FunctionMockerLe;

class UnitTestCase extends Unit
{
    use ProphecyTrait;

    protected $tester;

    /**
     * @var Prophet
     */
    protected $prophecy;

    /**
     * @var ObjectProphecy
     */
    protected $config;

    protected function getConfig(): Config
    {
        return $this->config->reveal();
    }

    /**
     * @var ObjectProphecy
     */
    protected $dispatcher;

    protected function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher->reveal();
    }

    /**
     * @var ObjectProphecy
     */
    protected $file;

    protected function getFile(): SplFileObject
    {
        return $this->file->reveal();
    }

    /**
     * @var ObjectProphecy
     */
    protected $image;

    protected function getImage(): Image
    {
        return $this->image->reveal();
    }

    protected $is_admin = false;
    protected $is_feed = false;
    protected $is_preview = false;

	// phpcs:ignore -- Method from Codeception
	protected function _before(): void
    {
        FunctionMockerLe\define('is_admin', function (): bool {
            return $this->is_admin;
        });
        FunctionMockerLe\define('is_feed', function (): bool {
            return $this->is_feed;
        });
        FunctionMockerLe\define('is_preview', function (): bool {
            return $this->is_preview;
        });

        $this->prophecy = new Prophet();

        $this->config = $this->prophecy->prophesize(Config::class);
        $this->dispatcher = $this->prophecy->prophesize(EventDispatcherInterface::class);
        $this->file = $this->prophecy->prophesize(SplFileObject::class);
        $this->image = $this->prophecy->prophesize(Image::class);
    }

    protected function _after()
    {
        FunctionMockerLe\undefineAll(['is_admin','is_feed','is_preview']);
    }
}
