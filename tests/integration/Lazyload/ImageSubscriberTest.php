<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Lazyload;

use ItalyStrap\Lazyload\ImageSubscriber;
use ItalyStrap\Tests\IntegrationTestCase;
use PHPUnit\Framework\Assert;

class ImageSubscriberTest extends IntegrationTestCase
{
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

    public function filteredContentHasLazyload()
    {
        $this->dispatcher->addListener('italystrap_lazyload_image_events', function (array $events) {
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

        Assert::assertStringMatchesFormat($expected, $filtered, '');
    }

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

        Assert::assertStringMatchesFormat($expected, $filtered, '');
    }
}
