<?php

/**
 * ItalyStrap Carousel initially forked from Agnosia Bootstrap Carousel by AuSoft
 *
 * Display a Bootstrap Carousel based on selected images and their titles and
 * descriptions. You need to include the Bootstrap CSS and Javascript files on
 * your own; otherwise the class will not work.
 *
 * @todo https://codex.wordpress.org/it:Shortcode_Gallery Aggiungere parametri mancanti
 *
 * @package ItalyStrap\Core
 * @version 1.0
 * @since   2.0
 */

namespace ItalyStrap\Carousel;

/**
 * The Carousel Bootsrap class
 */
class Bootstrap extends Carousel
{
    /**
     * Initialize the contstructor
     * @param array $args The carousel attribute.
     */
    function __construct($args)
    {

        parent::__construct($args);
    }
}
