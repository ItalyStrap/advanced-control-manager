<?php

/**
 * Fonts API for Google Fonts
 *
 * This class load the google fonts for style purpose.
 *
 * @TODO https://developer.wordpress.org/reference/functions/add_editor_style/#comment-1172
 *
 * @link www.italystrap.com
 * @since 2.5.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Google;

if (! defined('ITALYSTRAP_PLUGIN') or ! ITALYSTRAP_PLUGIN) {
    die();
}

use ItalyStrap\Config\ConfigInterface;

/**
 * Web Font Loading class
 */
class Fonts
{
    /**
     * Google API font URL
     *
     * @var string
     */
    protected $google_api_url = '';

    /**
     * Google API Key
     *
     * @var string
     */
    protected $google_api_key = '';

    /**
     * relative path to the file json with all fonts preloaded
     *
     * @var string
     */
    protected $api_fallback_file = '';

    /**
     * Configuration for google http query params.
     *
     * @var array
     */
    protected $http_query = [];

    protected static $fonts = [];

    /**
     * Init the class.
     *
     * @param ConfigInterface $config The class configuration.
     */
    function __construct(ConfigInterface $config)
    {

        $this->google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts';

        $this->google_api_key = esc_attr($config->get('google_api_key', ''));

        $this->http_query = ['key'   => $this->google_api_key];

        $this->api_fallback_file = __DIR__ . DS . 'fonts/fonts.json';

        $this->variants = ['100'       => __('100', 'italystrap'), '100italic' => __('100italic', 'italystrap'), '200'       => __('200', 'italystrap'), '200italic' => __('200italic', 'italystrap'), '300'       => __('300', 'italystrap'), '300italic' => __('300italic', 'italystrap'), 'regular'   => __('Regular 400', 'italystrap'), 'italic'    => __('Italic 400', 'italystrap'), '500'       => __('500', 'italystrap'), '500italic' => __('500italic', 'italystrap'), '600'       => __('600', 'italystrap'), '600italic' => __('600italic', 'italystrap'), '700'       => __('700', 'italystrap'), '700italic' => __('700italic', 'italystrap'), '800'       => __('800', 'italystrap'), '800italic' => __('800italic', 'italystrap'), '900'       => __('900', 'italystrap'), '900italic' => __('900italic', 'italystrap')];

        $this->subsets = ['bengali'       => __('Bengali', 'italystrap'), 'cyrillic'      => __('Cyrillic', 'italystrap'), 'cyrillic-ext'  => __('Cyrillic Extended', 'italystrap'), 'devanagari'    => __('Devanagari', 'italystrap'), 'greek'         => __('Greek', 'italystrap'), 'greek-ext'     => __('Greek Extended', 'italystrap'), 'gujarati'      => __('Gujarati', 'italystrap'), 'hebrew'        => __('Hebrew', 'italystrap'), 'khmer'         => __('Khmer', 'italystrap'), 'latin'         => __('Latin', 'italystrap'), 'latin-ext'     => __('Latin Extended', 'italystrap'), 'tamil'         => __('Tamil', 'italystrap'), 'telugu'        => __('Telugu', 'italystrap'), 'thai'          => __('Thai', 'italystrap'), 'vietnamese'    => __('Vietnamese', 'italystrap')];
    }

    /**
     * Flush transient
     * Maybe flush at the 'update_option_' . $args['options_name'] ?
     */
    public function flush_transient()
    {
        delete_transient('italystrap_google_fonts');
    }

    /**
     * Get the google fonts from the API or in the cache
     *
     * @return array
     */
    public function get_remote_fonts()
    {

        if (empty($this->google_api_key)) {
            return (array) $this->get_api_fallback()->items;
//          return array();
        }

        // $this->flush_transient();

        if (! self::$fonts) {
            self::$fonts = get_transient('italystrap_google_fonts');
        }

        if (false === self::$fonts) {
            $font_content = wp_remote_get($this->google_api_url . '?' . http_build_query($this->http_query), ['sslverify' => false]);

            /**
             * In case the url of the google fonts is wrong
             * For example when is missing 'https:'
             */
            if (is_wp_error($font_content)) {
                return (array) $this->get_api_fallback()->items;
//              return array();
            }

            self::$fonts = wp_remote_retrieve_body($font_content);

            self::$fonts = (array) json_decode(self::$fonts, null, 512, JSON_THROW_ON_ERROR);

            self::$fonts = $this->rename_key_by_font_family_name(self::$fonts);

            set_transient('italystrap_google_fonts', self::$fonts, MONTH_IN_SECONDS);

            /**
             * Commented for better test in future
             */
            // $this->set_api_fallback( self::$fonts );
        }

        /**
         * self::$fonts->items or self::$fonts['items'] have to always return an array
         */

        if (is_object(self::$fonts)) {
            return self::$fonts->items;
        }

        if (! isset(self::$fonts['items'])) {
            return [];
        }

        return (array) self::$fonts['items'];
    }

    /**
     * If the we don't have a Google API key, or the request fails,
     * use the contents of this file instead.
     *
     * @author Paul Clark
     * @link http://stylesplugin.com
     * @file styles/styles-font-menu/classes/sfm-group-google.php
     */
    public function get_api_fallback()
    {
        return json_decode(file_get_contents($this->api_fallback_file), null, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Save Google Fonts API response to file for cases where we
     * don't have an API key or the API request fails
     *
     * @author Paul Clark
     * @link http://stylesplugin.com
     * @file styles/styles-font-menu/classes/sfm-group-google.php
     */
    public function set_api_fallback($fonts)
    {
        if (! file_exists($this->api_fallback_file) || is_writable($this->api_fallback_file)) {
            file_put_contents($this->api_fallback_file, json_encode($fonts, JSON_THROW_ON_ERROR));
        }
    }

    /**
     * Get property
     *
     * @param  string $prop_name The name of the property to get.
     * @return string            The value pf the property.
     */
    public function get_property($prop_name)
    {

        if (! property_exists($this, $prop_name)) {
            return null;
        }

        return $this->$prop_name;
    }

    /**
     * Rename array key by font family name
     *
     * @param  array $items The array with Google fonts.
     * @return array        Return the new array
     */
    protected function rename_key_by_font_family_name(array $items = [])
    {

        if (empty($items['items'])) {
            return $items;
        }

        $i = 0;
        foreach ($items['items'] as $key => $object) {
            $items['items'][ $object->family ] = $object;
            unset($items['items'][ $i ]);
            $i++;
        }

        return $items;
    }
}
