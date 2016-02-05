<?php namespace ItalyStrap\Core;
/**
 * @deprecated 2.0 Deprecated Class
 */


if ( ! class_exists( 'ItalyStrapSitemaps' ) ) {

	/**
	* 
	*/
	class ItalyStrapHTMLSitemaps extends Sitemaps_HTML{

		
		function __construct( $args = array() ){

			_deprecated_function( __CLASS__, '2.0', 'ItalyStrap\\\Core\\\Sitemaps_HTML' );

			parent::__construct( $args );

		}

	}// class end

}// if