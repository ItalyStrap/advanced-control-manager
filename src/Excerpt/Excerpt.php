<?php

/**
 * Excerpt API class.
 *
 * This class manage the output of the excerpt and read more link.
 *
 * @link www.italystrap-com
 * @since 3.x.x
 *
 * @package ItalyStrap\Core\Excerpt
 */

namespace ItalyStrap\Excerpt;

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Core;
use ItalyStrap\I18N\Translatable;

/**
 * New Class to set excerpt lenght and show "more link"
 * @link http://stackoverflow.com/questions/10081129/why-cant-i-override-wps-excerpt-more-filter-via-my-child-theme-functions
 *
 * Codex link:
 * @link http://codex.wordpress.org/Excerpt
 * @link http://codex.wordpress.org/Customizing_the_Read_More
 *
 * The quicktag <!--more--> doesn't work with the_excerpt() and get_the_excerpt(),
 * it works only with the_content and get_the_content
 * Use the box excerpt inside admin panel
 *
 * @todo Generating auto description from content https://gist.github.com/palimadra/3023928
 */
class Excerpt implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked excerpt_length - 10
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        $options = self::$config->all();

        $events = [
            // 'hook_name'                          => 'method_name',
            'excerpt_length'    => 'excerpt_length',
            'excerpt_more'      => '__return_empty_string',
            'wp_trim_words' => ['function_to_add'   => 'excerpt_end_with_punctuation', 'priority'          => 98, 'accepted_args'     => 4],
        ];

        $filter = '';

        if ('append' === $options['read_more_position']) {
            $filter = 'get_the_excerpt';
        } elseif ('after' === $options['read_more_position']) {
            $filter = 'the_excerpt';
        }

        $events[ $filter ] = 'custom_excerpt_more';

        return $events;
    }

    /**
     * Theme settings
     */
    private array $options = [];

    /**
     * Configuration object
     *
     * @var Config
     */
    private static $config;

    /**
     * Translator Object
     */
    private \ItalyStrap\I18N\Translatable $translator;

    /**
     * Init the class
     *
     * @param $options $argument [description].
     */
    function __construct(ConfigInterface $config, Translatable $translator)
    {

        $this->translator = $translator;

        self::$config = $config;

        $this->options = self::$config->all();
    }

    /**
     * Is Read More global check
     *
     * @return bool Return true if the global read more is active from settings.
     */
    public function is_global_read_more_active()
    {
        return (bool) $this->options['activate_excerpt_more_mods'];
    }

    /**
     * Escerpt read more link function
     *
     * @hoocked excerpt_more - 10
     *
     * @return string Return link to post in read more.
     */
    public function read_more_link($context = 'excerpt_read_more', array $attr = [], $link_text = '')
    {

        /**
         * CSS class for read more link. Default 'none'.
         *
         * @var string
         */
        $class = apply_filters('italystrap_read_more_class', $this->options['read_more_class']);

        $link_text = '' === $link_text
            ? $this->translator->getString('read_more_link_text', $this->options['read_more_link_text'])
            : $link_text;

        $default = ['class' => $class, 'href'  => get_permalink(), 'rel'   => 'prefetch'];

        $attr = array_merge($default, $attr);

        return sprintf(
            apply_filters('italystrap_read_more_link_pattern', ' <a %1$s>%2$s</a>'),
            Core\get_attr($context, $attr, false),
            esc_html(apply_filters('italystrap_read_more_link_text', $link_text))
        );
    }

    /**
     * Function to override
     * @param  string $output Get excerpt output.
     *
     * @hoocked get_the_excerpt - 10
     *
     * @see ItalyStrap\Core\Excerpt\Excerpt::read_more_link()
     *
     * @return string         Return output with read more link
     */
    public function custom_excerpt_more($output)
    {

        if (is_attachment()) {
            return $output;
        }

        $output .= $this->read_more_link();

        return $output;
    }

    /**
     * Excerpt lenght function
     *
     * @param  int $length Get the defautl words number.
     *
     * @hoocked excerpt_length - 10
     *
     * @return int         Return words number for excerpt
     */
    public function excerpt_length($length)
    {

        if (is_home() || is_front_page() || is_archive()) {
            $length = (int) $this->options['excerpt_length'];
        }

        return $length;
    }

    /**
     * Filters the text content and trim it at the last punctuation.
     *
     * @param string  $text          The trimmed text.
     * @param int     $num_words     The number of words to trim the text to.
     *                               Default 5.
     * @param string  $more          An optional string to append to the end of
     *                               the trimmed text, e.g. &hellip;.
     * @param string  $original_text The text before it was trimmed.
     *
     * @return string                Trimmed text.
     */
    public function excerpt_end_with_punctuation($text, $num_words, $more, $original_text)
    {

        if (empty($this->options['end_with_punctuation'])) {
            return $text;
        }

        /**
         * If text is empty exit.
         */
        if (empty($text)) {
            return $text;
        }

        /**
         * First trim the "more" tags
         *
         * @var string
         */
        $text = str_replace($more, '', $text);

        /**
         * Paragraphs often end with space ' '.
         * The space at the end of the punctuation prevents that links like www.mysite.com it will be trimmed.
         *
         * @var array
         */
        $needles = apply_filters('italystrap_excerpt_end_punctuation_pattern', ['. ', '? ', '! ']);

        $found = false;

        foreach ($needles as $punctuation) {
            /**
             * Return early
             */
            if ($found = strrpos($text, (string) $punctuation)) {
                return substr($text, 0, $found + 1) . $more;
            }
        }

        return $text . $more;
    }

    /**
     * Return empty string
     *
     * @return null        Return empty string
     */
    public function __return_empty_string($content = '')
    {
        return '';
    }
}
