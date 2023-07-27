<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Lazyload;

use ItalyStrap\Lazyload\Image;
use ItalyStrap\Tests\UnitTestCase;
use Prophecy\Argument;

class ImagesTest extends UnitTestCase
{
    private function getInstance()
    {
        $sut = new Image(
            $this->getConfig(),
            $this->getDispatcher()
        );
        $this->assertInstanceOf(Image::class, $sut, '');
        return $sut;
    }

    /**
     * @test
     */
    public function itShouldBeInstantiable()
    {
        $sut = $this->getInstance();
    }

    public function imageProvider(): iterable
    {
        return [
            'simple image'  => [
                '<img src="some/image/uri">',
                <<<'IMG'
<img src="PLACEHOLDER" data-lazy-src="some/image/uri"><noscript><img src="some/image/uri"></noscript><meta itemprop="image" content="some/image/uri"/>
IMG
            ],
            'data-lazy-srcset'  => [
                '<img src="some/image/uri" srcset="some/image/uri">',
                <<<'IMG'
<img src="PLACEHOLDER" data-lazy-src="some/image/uri" data-lazy-srcset="some/image/uri"><noscript><img src="some/image/uri" srcset="some/image/uri"></noscript><meta itemprop="image" content="some/image/uri"/>
IMG
            ],
            'data-sizes'    => [
                '<img src="some/image/uri" srcset="some/image/uri" sizes="500">',
                <<<'IMG'
<img src="PLACEHOLDER" data-lazy-src="some/image/uri" data-lazy-srcset="some/image/uri" data-sizes="500"><noscript><img src="some/image/uri" srcset="some/image/uri" sizes="500"></noscript><meta itemprop="image" content="some/image/uri"/>
IMG
            ],
        ];
    }

    /**
     * @test
     * @dataProvider imageProvider()
     */
    public function itShouldReplaceImageWith($image, $expected)
    {

        $this->dispatcher->filter(Argument::type('string'), Argument::any())->willReturn('PLACEHOLDER');

        $sut = $this->getInstance();
        $replaced = $sut->replaceSrcImageWithSrcPlaceholders($image);
        $this->assertStringContainsString($replaced, $expected, '');
    }

    public function frontProvider()
    {
        return [
            'is feed'   => [
                true,
                false,
            ],
            'is preview'    => [
                false,
                true,
            ],
            'is feed and preview'   => [
                true,
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider frontProvider()
     */
    public function itShouldReturnContentIsIsAdminTrue($feed, $preview)
    {

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
    public function itShouldReturnContentIfHasAlreadyLazyLoaded()
    {

        $content = '<img src="some/image/uri" data-lazy-src="some/image/uri">';

        $sut = $this->getInstance();
        $replaced = $sut->replaceSrcImageWithSrcPlaceholders($content);
        $this->assertSame($content, $replaced, '');
    }
}
