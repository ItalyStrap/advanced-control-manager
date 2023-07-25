<?php

/**
 * Posts Block API
 *
 * Block for Posts Class
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Blocks;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Query\Posts as Posts_Base;

/**
 * Posts Block API
 */
class Posts extends Block
{
    /**
     * [$var description]
     *
     * @var null
     */
    private $var = null;

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    public function __construct(Posts_Base $post, $block_type, $args = [])
    {
        $this->posts = $post;
        parent::__construct($block_type, $args);
    }

    /**
     * Render the output
     *
     * @param  array  $attr    The attribute for the shortcode.
     * @param  array  $content The content for the shortcode.
     *
     * @return string          The output of the shortcode.
     */
    public function render($attributes = [], $content = '')
    {

        $this->posts->get_widget_args($attributes);

        return $this->posts->output();
    }
}
