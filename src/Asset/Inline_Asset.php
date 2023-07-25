<?php

/**
 * Abstract class API for inline style and script
 *
 * This class define method for printing style and script inline.
 *
 * @link  http://coderrr.com/php-passing-data-between-classes/
 *
 * @since 2.0.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Asset;

/**
 * Class description
 */
abstract class Inline_Asset implements Inline_Asset_Interface
{
    /**
     * The data that will be printend inline.
     *
     * @var string
     */
    protected static $data = '';

    /**
     * This append new data to the static variable.
     *
     * @param string $data The data static variable.
     */
    public static function set($data)
    {

        return static::$data .= $data;
    }

    /**
     * This return the data variable.
     */
    public static function get()
    {

        return static::$data;
    }
}
