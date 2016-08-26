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
class ItalyStrapGlobals extends Inline_Script {

    // public static $data = '';
 
    // public static function set( $data ){

    //     return static::$data .= $data;

    // }
     
    // public static function get(){
    //     echo "<pre>";
    //     print_r( static::$data );
    //     echo "</pre>";
    //     return static::$data;

    // }
}

class ItalyStrapGlobalsCss extends Inline_Style {

    // public static $data = '';
 
    // public static function set( $data ){

    //     return static::$data .= $data;

    // }
     
    // public static function get(){
    //     echo "<pre>";
    //     print_r( static::$data );
    //     echo "</pre>";
    //     return static::$data;

    // }
}

// class ItalyStrapGlobals{

//     public static $global = '';
 
//     public static function set( $data ){

//         return self::$global .= $data;

//     }
     
//     public static function get(){

//         return self::$global;

//     }
// }

// class ItalyStrapGlobalsCss{

//     public static $global = '';
 
//     public static function set( $data ){

//         return self::$global .= $data;

//     }
     
//     public static function get(){

//         return self::$global;

//     }
// }