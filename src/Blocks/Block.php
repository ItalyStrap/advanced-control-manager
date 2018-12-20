<?php
/**
 * Block API
 *
 * Class for creating the new block for gutemberg
 *
 * @link [URL]
 * @since [x.x.x (if available)]
 *
 * @package [Plugin/Theme/Etc]
 */

namespace ItalyStrap\Blocks;

/**
 * Block API
 */
abstract class Block {

	/**
	 * Render
	 *
	 * @param  string $value [description]
	 * @return string        [description]
	 */
	public function render( array $attributes ) {
		return 'Abstract Block';
	}
}
