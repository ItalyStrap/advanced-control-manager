<?php

declare(strict_types=1);

namespace ItalyStrap\Migrations;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Event\SubscriberInterface;

class EventsMigration implements SubscriberInterface, Subscriber_Interface
{
    public static function get_subscribed_events()
    {
        return [
            'init'  => 'convert',
        ];
    }

    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked 'init' - 10
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return self::get_subscribed_events();
    }

    /**
     * Hooks from version <4.0.0
     */
    private array $hooks = [
        'body_open'                 => '',
        'wrapper_open'              => 'italystrap_before',
        'header_open'               => 'italystrap_before_header',
        'header_closed'             => 'italystrap_after_header',
        'nav_open'                  => '',
        'before_wp_nav_menu'        => '',
        'after_wp_nav_menu'         => '',
        'nav_closed'                => '',
        'content_open'              => '',
        'content_container_open'    => '',
        'content_col_open'          => 'italystrap_before_loop',
        'content_col_closed'        => 'italystrap_after_loop',
        'sidebar_col_open'          => 'italystrap_before_sidebar_widget_area',
        'sidebar_col_closed'        => 'italystrap_after_sidebar_widget_area',
        'content_container_closed'  => '',
        'footer_open'               => 'italystrap_before_footer',
        'footer_closed'             => 'italystrap_after_footer',
        'wrapper_closed'            => 'italystrap_after',
        'body_closed'               => ''
    ];

    public function convert()
    {

        if (!\function_exists('do_action_deprecated')) {
            return;
        }

        foreach ($this->hooks as $old => $new) {
            if (empty($new)) {
                continue;
            }
            \add_action($new, function () use ($new, $old) {
                if (!\has_filter($old)) {
                    return;
                }

                \_deprecated_hook(
                    $old,
                    '4.0.0',
                    $new,
                    \sprintf(
                        /** translators: %1$s is the old hook name, %2$s is the new hook name */
                        \__('%s is deprecated, use %s instead.', 'italystrap'),
                        $old,
                        $new
                    )
                );
                \do_action($old);
            });
        }
    }
}
