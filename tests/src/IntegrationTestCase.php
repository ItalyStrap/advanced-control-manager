<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\TestCase\WPTestCase;
use DOMDocument;
use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcher;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Lazyload\Image;
use ItalyStrap\Update\Validation;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use SplFileObject;

class IntegrationTestCase extends WPTestCase
{
    use ProphecyTrait;

    protected $tester;

    /**
     * @var DOMDocument
     */
    private $dom;

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

    private $image;

    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return SplFileObject
     */
    public function getFile(): SplFileObject
    {
        return $this->file->reveal();
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->dom = new DOMDocument();

        $this->validation = new Validation();

        $this->config = new Config();
        $this->dispatcher = new EventDispatcher();
        $this->file = $this->prophesize(SplFileObject::class);
        $this->image = new Image($this->config, $this->dispatcher);
    }

    public function tearDown(): void
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }
}
