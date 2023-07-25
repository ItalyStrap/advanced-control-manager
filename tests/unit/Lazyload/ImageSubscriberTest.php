<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Lazyload;

use ItalyStrap\Lazyload\ImageSubscriber;
use ItalyStrap\Tests\UnitTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class ImageSubscriberTest extends UnitTestCase
{
    use ProphecyTrait;

    private function getInstance()
    {
        $sut = new ImageSubscriber(
            $this->getConfig(),
            $this->getDispatcher(),
            $this->getFile(),
            $this->getImage()
        );
        $this->assertInstanceOf(ImageSubscriber::class, $sut, '');
        return $sut;
    }

    /**
     * @test
     */
    public function itShouldBeInstantiable()
    {
        $sut = $this->getInstance();
    }

    /**
     * @test
     */
    public function itShouldExecuteInit()
    {

        $this->config->get(Argument::any(), false)->willReturn(false);

        $this->dispatcher->addListener(
            Argument::type('string'),
            Argument::type('callable'),
            Argument::any(),
            Argument::any()
        )->will(fn($args): bool => true)->shouldBeCalled();

        $this->dispatcher->filter('italystrap_lazyload_image_events', Argument::any())->will(fn($args) => $args[1])->shouldBeCalled();

        $sut = $this->getInstance();
        $sut->onWpLoaded();
    }
}
