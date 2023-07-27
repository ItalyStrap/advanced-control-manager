<?php

/**
 * Interface for Fields
 *
 * This is the interface for fields class
 *
 * @link [URL]
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Query;

interface Query_Interface
{
    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function render();
}
