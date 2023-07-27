<?php

/**
 * Image lazy load forked from https://wordpress.org/plugins/lazy-load/
 * This function is used for lazy loading every image
 * It works with unveil.js (Not jquery.sonar)
 * @link http://luis-almeida.github.io/unveil/
 */

namespace ItalyStrap\Lazyload;

use ItalyStrap\Config\Config;
use ItalyStrap\Event\EventDispatcherInterface;
use ItalyStrap\Event\Subscriber_Interface;
use SplFileInfo;

class ImageSubscriber implements Subscriber_Interface
{
    private \ItalyStrap\Event\EventDispatcherInterface $dispatcher;

    private \ItalyStrap\Config\Config $config;

    /**
     * @var \SplFileObject
     */
    private \SplFileInfo $file;

    private \ItalyStrap\Lazyload\Image $image;

    /**
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            'wp_loaded' => 'onWpLoaded',
        ];
    }

    /**
     * Init constructor
     *
     * @param Config $config
     * @param EventDispatcherInterface $dispatcher
     * @param SplFileInfo $file
     * @param Image $image
     */
    public function __construct(
        Config $config,
        EventDispatcherInterface $dispatcher,
        SplFileInfo $file,
        Image $image
    ) {
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->file = $file;
        $this->image = $image;
    }

    public function onWpLoaded(): void
    {

        if (\is_admin()) {
            return;
        }

        $events = [
            [
                'post_gallery',
                9
            ],
            'lazyload_widget_text'  => [
                'widget_text',
                PHP_INT_MAX - 1
            ],
            [
                'widget_html_code_content',
                PHP_INT_MAX - 1
            ],
            [
                'the_content',
                PHP_INT_MAX - 1
            ],
            [
                'post_thumbnail_html',
                11
            ],
            [
                'get_avatar',
                11
            ],
            ['italystrap_custom_header_image'],
            ['italystrap_carousel_output', PHP_INT_MAX],
            ['italystrap_lazyload_images_in_this_content', PHP_INT_MAX],
        ];

        $events = $this->dispatcher->filter('italystrap_lazyload_image_events', $events);

        \array_walk($events, function (array $event, $index): void {

            if ($this->config->get($index, false)) {
                return;
            }

            $this->dispatcher->addListener(
                $event[0],
                [$this->image, 'replaceSrcImageWithSrcPlaceholders'],
                $event[1] ?? 10,
                1
            );
        });

        $this->dispatcher->addListener('italystrap_custom_inline_script', fn(string $script) => $script . $this->script(), 10, 1);

        $this->dispatcher->addListener('italystrap_custom_inline_style', fn(string $style) => $style . $this->style(), 10, 1);
    }

    /**
     * @param $content
     * @return string
     */
    private function script(): string
    {
        return $this->file->fread($this->file->getSize());
    }

    /**
     * Add css to dull an img before it is appended to src
     * This apply opacity 0 only for those images that have the data-lazy-src attributes
     * normally added from this plugin.
     *
     * @return string Add opacity and transition to img
     */
    private function style(): string
    {
        return 'img[data-lazy-src]{opacity:0;transition:opacity .3s ease-in;}';
    }
}
