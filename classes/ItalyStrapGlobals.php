<?php
class ItalyStrapGlobals{

    public static $global = '';
 
    public static function set( $data ){

        return self::$global .= $data;

    }
     
    public static function get(){

        return self::$global;

    }
}