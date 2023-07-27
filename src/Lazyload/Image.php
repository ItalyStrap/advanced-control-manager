<?php

declare(strict_types=1);

namespace ItalyStrap\Lazyload;

use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcherInterface;

/**
 * Class Image
 * @package ItalyStrap\Lazyload
 */
class Image
{
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
    public const DEFAULT_PLACEHOLDER = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    public const PLACEHOLDER_FILTER_EVENT_NAME = 'italystrap_lazy_load_placeholder_image';

    private \ItalyStrap\Config\Config $config;

    private \ItalyStrap\Event\EventDispatcherInterface $dispatcher;

    /**
     * Init constructor
     *
     * @param Config $config
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        Config $config,
        EventDispatcherInterface $dispatcher
    ) {

        $this->config = $config;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Add image placeholders to image src
     *
     * @param string $content Content to be processed
     * @return string
     */
    public function replaceSrcImageWithSrcPlaceholders(string $content): string
    {

        if ($this->isNotTheFrontEnd()) {
            return $content;
        }

        if ($this->hasAlreadyLazyLoadedIn($content)) {
            return $content;
        }

        /**
         * This is a pretty simple regex, but it works
         *
         * @var string
         */
        return \preg_replace_callback(
            '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#',
            fn(array $matches) => $this->replaceAttributes($matches),
            $content
        );
    }

    /**
     * @param array $matches
     * @return string
     */
    private function replaceAttributes(array $matches)
    {

        /**
         * Replace srcset, sizes and src attributes
         */
        $content = \str_replace(
            ['srcset', 'sizes'],
            ['data-lazy-srcset', 'data-sizes'],
            \sprintf(
                '<img%1$ssrc="%2$s" data-lazy-src="%3$s"%4$s>',
                $matches[1],
                $this->getImagePlaceholder(),
                $matches[2],
                $matches[3]
            )
        );

        return $this->appendNoscript($matches, $content);
    }

    /**
     * @param array $matches
     * @param string $content
     * @return string
     */
    private function appendNoscript(array $matches, string $content): string
    {
        $content .= \sprintf(
            '<noscript><img%1$ssrc="%2$s"%3$s></noscript><meta itemprop="image" content="%2$s"/>',
            $matches[ 1 ],
            $matches[ 2 ],
            $matches[ 3 ]
        );

        return $content;
    }

    /**
     * @return string
     */
    private function getImagePlaceholder()
    {
        return $this->dispatcher->filter(
            static::PLACEHOLDER_FILTER_EVENT_NAME,
            $this->config->get('lazyload-custom-placeholder', static::DEFAULT_PLACEHOLDER)
        );
    }

    /**
     * @param string $content
     * @return bool
     */
    private function hasAlreadyLazyLoadedIn(string $content): bool
    {
        return false !== \strpos($content, 'data-lazy-src');
    }

    /**
     * @return bool
     */
    private function isNotTheFrontEnd(): bool
    {
        return \is_feed() || \is_preview();
    }
}
