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

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Core;

/**
 * Minify API Class
 */
class Minify
{
    /**
     * Search pattern.
     */
    private array $search = ["\r\n", "\n", "\r", "\t", '&nbsp;', '&amp;nbsp;'];

    /**
     * Replace pattern.
     */
    private array $replace = [
        ': '    => ':',
        '; '    => ';',
        ' {'    => '{',
        ' }'    => '}',
        ', '    => ',',
        '{ '    => '{',
        ';}'    => '}',
        // Strip optional semicolons.
        ",\n"   => ',',
        // Don't wrap multiple selectors.
        "\n}"   => '}',
        // Don't wrap closing braces.
        '} '    => '}',
    ];

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
    protected function parse($subject)
    {

        /**
         * Return the original content if DEBUG is active
         */
        if (Core\is_script_debug()) {
            $is_debug = sprintf(
                '/** %s */',
                __('The script debug for this site is active, if you want to minify this snippet set the SCRIPT_DEBUG constant in wp-config.php to false.', 'domain')
            );
            return "\n" . $is_debug . "\n" . $subject;
        }

        $comments = [
            '#/\*.*?\*/#s' => '',
            // Strip C style comments.
            '#\s\s+#'      => ' ',
        ];

        $search = array_keys($comments);
        $subject = preg_replace($search, $comments, $subject);

        $search = array_keys($this->replace);
        $subject = str_replace($search, $this->replace, $subject);

        return trim($subject);
    }

    /**
     * Wrapper for the CLASS::parse();
     *
     * @param  string $subject The subject output.
     *
     * @return string          The subject minified
     */
    public function js($subject)
    {
        return $this->parse($subject);
    }

    /**
     * Wrapper for the CLASS::parse();
     *
     * @param  string $subject The subject output.
     *
     * @return string          The subject minified
     */
    public function css($subject)
    {
        return $this->parse($subject);
    }

    /**
     * Wrapper for the CLASS::parse();
     *
     * @param  string $subject The subject output.
     *
     * @return string          The subject minified
     */
    public function html($subject)
    {
        return $this->parse($subject);
    }
}
