<?php

/**
 * Interface for Admin Class
 *
 * This is the interface for the admin class
 *
 * @link [URL]
 * @since 2.3.0
 *
 * @package ItalyStrap\Settings
 */

namespace ItalyStrap\Update;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

interface Update_Interface
{
    /**
     * Sanitize the input data
     *
     * @since 2.0.0
     *
     * @param  array $input The input array.
     * @return array        Return the array sanitized
     */
    public function update(array $instance = [], array $fields = []);
}
