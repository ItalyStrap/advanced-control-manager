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

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

use \ItalyStrap\Query\Posts as Posts_Base;

/**
 * Posts Block API
 */
class Posts extends Block {

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
	function __construct( Posts_Base $post ) {
		// Code...
	}
}
