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
abstract class Block extends \WP_Block_Type
{
    public function __construct($block_type, $args = [])
    {

        $args = array_merge(
            [
                'render_callback' => [ $this, 'render' ]
            ],
            $args
        );

        parent::__construct($block_type, $args);
    }

    /**
     * Render
     *
     * @param  string $value [description]
     * @return string        [description]
     */
//  public function render( array $attributes ) {
//      return 'Abstract Block';
//  }
}
