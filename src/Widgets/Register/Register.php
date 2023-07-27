<?php

/**
 * Register Widget Class
 *
 * Class for adding widget to $wp_widget_factory; from an idea of Luca Tume
 * @link http://theaveragedev.com/widget-classes-dependency-injection-02/
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package Italystrap
 */

namespace ItalyStrap\Widgets\Register;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Widget Register Class
 */
class Register
{
    /**
     * Widget object
     *
     * @var object
     */
    protected $widget;

    /**
     * Init the class
     *
     * @param object $widget The object of the widget.
     */
    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    public function hook()
    {
        add_action('widgets_init', [$this, 'register_widgets']);
    }

    public function register_widgets()
    {
        global $wp_widget_factory;

        $wp_widget_factory->widgets[ get_class($this->widget) ] = $this->widget;
    }
}
