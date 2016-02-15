<?php namespace ItalyStrap\Core;
/**
 * Init API: Init Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
    die();
}

/**
 * This is a static class for append some inline javascript and print it in footer
 * @link  http://coderrr.com/php-passing-data-between-classes/
 */
class ItalyStrapGlobals{

    public static $global = '';
 
    public static function set( $data ){

        return self::$global .= $data;

    }
     
    public static function get(){

        return self::$global;

    }
}

class ItalyStrapGlobalsCss{

    public static $global = '';
 
    public static function set( $data ){

        return self::$global .= $data;

    }
     
    public static function get(){

        return self::$global;

    }
}