<?php

/**
 * Shortcode API
 *
 * Shortcode Base class
 *
 * Some ideas http://www.wpbeginner.com/beginners-guide/7-essential-tips-for-using-shortcodes-in-wordpress/
 *
 * @link www.italystrap.com
 * @since 2.4.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Shortcodes;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

/**
 * Shortcode
 */
abstract class Shortcode
{
    protected $attr = [];

    protected $default = [];

    protected $content = '';

    public $shortcode_ui = [];

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    public function __construct($argument = null)
    {
        // Code...
    }
}
