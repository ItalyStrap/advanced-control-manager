<?php
/**
 * API for generating CSS with PHP
 *
 * This class manage the CSS creation and put it in the HTML of your page.
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Asset;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use ItalyStrap\Event\Subscriber_Interface;

/**
 * Minify API Class
 */
class Minify {

	/**
	 * Search pattern.
	 *
	 * @var array
	 */
	private $search = array(
		"\r\n",
		"\n",
		"\r",
		"\t",
		'&nbsp;',
		'&amp;nbsp;',
	);

	/**
	 * Replace pattern.
	 *
	 * @var array
	 */
	private $replace = array(
		': '	=> ':',
		'; '	=> ';',
		' {'	=> '{',
		' }'	=> '}',
		', '	=> ',',
		'{ '	=> '{',
		';}'	=> '}', // Strip optional semicolons.
		",\n"	=> ',', // Don't wrap multiple selectors.
		"\n}"	=> '}', // Don't wrap closing braces.
		'} '	=> '}', // Put each rule on it's own line.
	);

	/**
	 * Minify the subject output
	 *
	 * @link https://www.progclub.org/blog/2012/01/10/compressing-css-in-php-no-comments-or-whitespace/ The used pattern.
	 *
	 * @link http://php.net/manual/en/function.ob-start.php Ci sono diversi esempi fra i commenti basta cercare in pagina "CSS"
	 *
	 * @param  string $subject The subject output.
	 *
	 * @return string          The subject minified
	 */
	public function run( $subject ) {

		$comments = array(
			'#/\*.*?\*/#s' => '',  // Strip C style comments.
			'#\s\s+#'      => ' ', // Strip excess whitespace.
		);

		$search = array_keys( $comments );
		$subject = preg_replace( $search, $comments, $subject );

		$search = array_keys( $this->replace );
		$subject = str_replace( $search, $this->replace, $subject );

		return trim( $subject );
	}
}
