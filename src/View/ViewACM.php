<?php

/**
 * View API: View Class
 *
 * @link italystrap.com
 * @package ItalyStrap\Views
 *
 * @since 2.10.0
 */

namespace ItalyStrap\View;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

/**
 * Template Class
 */
class ViewACM implements ViewACM_Interface
{
    private array $store = [];

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * Searches in the STYLESHEETPATH before TEMPLATEPATH and wp-includes/theme-compat
     * so that themes which inherit from a parent theme can just overload one file.
     *
     * @param string|array $template_names Template file(s) to search for, in order.
     * @param bool         $load           If true the template file will be loaded if it is found.
     * @param bool         $require_once   Whether to require_once or require. Default true. Has no effect if $load is false.
     *
     * @return string The template filename if one is located.
     */
    public function get($template_names, $load = false, $require_once = true)
    {
        return locate_template($template_names, $load, $require_once);
    }

    /**
     * Check if the template exists
     *
     * @param string|array $template_names Template file(s) to search for, in order.
     *
     * @return bool                        Return true if template exists
     */
    public function has($template_names)
    {
        return locate_template($template_names, false, true);
    }

    /**
     * Render a template file
     *
     * @param  string $template_file Path to the template file.
     * @param  array  $data          An array with data for the template.
     *
     * @return string                Return the template rendered
     */
    protected function render_template($template_file, array $data = [])
    {

        $object = (object) $data;
        $renderer = \Closure::bind(
            function ($template_file) {
                ob_start();
                include $template_file;
                return ob_get_clean();
            },
            $object
        );

        return $renderer($template_file);
    }

    /**
     * Load a template part into a template
     *
     * Makes it easy for a theme to reuse sections of code in a easy to overload way
     * for child themes.
     *
     * Includes the named template part for a theme or if a name is specified then a
     * specialised part will be included. If the theme contains no {slug}.php file
     * then no template will be included.
     *
     * The template is included using require, not require_once, so you may include the
     * same template part multiple times.
     *
     * For the $name parameter, if the file is called "{slug}-special.php" then specify
     * "special".
     *
     * @see get_template_part() - wp-includes/general-template.php
     *
     * @param string $slug The slug name for the generic template.
     * @param string $name The name of the specialised template.
     */
    public function render($slug, $name = '', $load = false, array $data = [])
    {
        /**
         * Fires before the specified template part file is loaded.
         *
         * The dynamic portion of the hook name, `$slug`, refers to the slug name
         * for the generic template part.
         *
         * @since 3.0.0
         *
         * @param string      $slug The slug name for the generic template.
         * @param string      $name The name of the specialized template.
         */
        do_action("italystrap_get_template_part_{$slug}", $slug, $name);

        $templates = [];

        if (! empty($name)) {
            $templates[] = "{$slug}-{$name}.php";
        }

        $templates[] = "{$slug}.php";

        if ($load) {
            $this->get($templates, $load, false);
            return;
        }

        $this->store[ $slug ] = $this->render_template($this->get($templates, $load, false), $data);

        echo $this->store[ $slug ];
    }
}
