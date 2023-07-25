<?php

/**
 * Update files API
 *
 * @link www.italystrap.it
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Migrations;

/**
 * Update_File
 */
class Update_File
{
    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function get_content_file($path)
    {
        return file_get_contents($path);
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function put_content_file($path, $data)
    {
        return file_put_contents($path, $data);
    }

    /**
     * Function description
     *
     * @param  string $value [description]
     * @return string        [description]
     */
    public function replace_content_file($old_string, $new_string, $data)
    {
        return str_replace($old_string, $new_string, $data);
    }
}
