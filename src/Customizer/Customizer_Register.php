<?php

/**
 * Customize_Manager
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Customizer;

if (! defined('ABSPATH') or ! ABSPATH) {
    die();
}

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Config\ConfigInterface;
use WP_Customize_Manager;
use ItalyStrap\Google\Fonts;

/**
 * Contains methods for customizing the theme customization screen.
 *
 * @todo https://codex.wordpress.org/Function_Reference/header_textcolor
 * @todo https://github.com/overclokk/wordpress-theme-customizer-custom-controls
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 * @since ItalyStrap 1.0
 */
class Customizer_Register implements Subscriber_Interface
{
    /**
     * Returns an array of hooks that this subscriber wants to register with
     * the WordPress plugin API.
     *
     * @hooked customize_register - 11
     *
     * @return array
     */
    public static function get_subscribed_events()
    {

        return [
            // 'hook_name'                          => 'method_name',
            'customize_register'    => ['function_to_add'   => 'register', 'priority'          => 11],
        ];
    }

    /**
     * $capability
     */
    private string $capability = 'edit_theme_options';

    /**
     * The plugin config
     *
     * @var array
     */
    private $config = [];

    /**
     * Init the class
     */
    function __construct(ConfigInterface $config, Fonts $web_fonts)
    {

        $this->config = $config;
        $this->web_fonts = $web_fonts;

        $this->fonts = $this->web_fonts->get_remote_fonts();
        $this->variants = $this->web_fonts->get_property('variants');
        $this->subsets = $this->web_fonts->get_property('subsets');
    }

    /**
     * This hooks into 'customize_register' (available as of WP 3.4) and allows
     * you to add new sections and controls to the Theme Customize screen.
     *
     * Note: To enable instant preview, we have to actually write a bit of custom
     * javascript. See live_preview() for more.
     *
     * @see add_action('customize_register',$func)
     * @param  object $wp_customize The cutomizer object.
     * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
     * @since ItalyStrap 2.0.0
     */
    public function register(WP_Customize_Manager $wp_customize)
    {

        /**
         * Define a new section for typography
         */
        $wp_customize->add_section(
            'fonts',
            [
                'title'             => __('Fonts', 'italystrap'),
                'description'       => __('First of all you have to add the Google API Key in the plugin settings page, after that you can select the font family you want to use, then you have to add some CSS to your style.css or in the settings section of this plugin.', 'italystrap'),
                // 'panel' => $this->panel, // Not typically needed.
                'priority'          => 160,
                'capability'        => $this->capability,
            ]
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'first_font_family',
            ['default'           => apply_filters('italystrap_first_font_family', ''), 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Web_Fonts_Control(
                $wp_customize,
                'first_font_family',
                ['label'         => __('The first font family to load', 'italystrap'), 'description'   => __('Select the first font family you want to load. Example: "Open Sans"', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'first_font_family', 'priority'      => 10, 'choices'       => $this->fonts]
            )
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'first_font_variants',
            ['default'           => 'regular', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Multiple_Control(
                $wp_customize,
                'first_font_variants',
                ['label'         => __('Weight of the first font family', 'italystrap'), 'description'   => __('Chose carefully the weight of the font family, multiple selection allowed (press CTRL and click). Performance tips: Do not load too much font weights.', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'first_font_variants', 'priority'      => 10, 'choices'       => $this->variants]
            )
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'first_font_subsets',
            ['default'           => 'latin', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Multiple_Control(
                $wp_customize,
                'first_font_subsets',
                ['label'         => __('Subsets of the first font family', 'italystrap'), 'description'   => __('Chose the subsets of the font family, default "latin", multiple selection allowed (press CTRL and click). Performance tips: Do not load too much font subsets.', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'first_font_subsets', 'priority'      => 10, 'choices'       => $this->subsets]
            )
        );

        /**
         * Select the menus_width of navbar
         */
        $wp_customize->add_setting(
            'first_typography',
            ['default'           => '', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );
        $wp_customize->add_control(
            'first_typography',
            ['settings'  => 'first_typography', 'label'         => __('Typography for the first font', 'italystrap'), 'description'   => __('Insert here one or more HTML tags or CSS selector separated by comma of the element you want to display this font. Example: <code>body</code> or <code>h1</code> or <code>h1,h2,h3,h4,h5,h6,.widget-title</code>', 'italystrap'), 'section'       => 'fonts', 'type'          => 'input']
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'second_font_family',
            ['default'           => apply_filters('italystrap_second_font_family', ''), 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Web_Fonts_Control(
                $wp_customize,
                'second_font_family',
                ['label'         => __('The Second font family to load', 'italystrap'), 'description'   => __('Select the second font family you want to load. Example: "Open Sans"', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'second_font_family', 'priority'      => 10, 'choices'       => $this->fonts]
            )
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'second_font_variants',
            ['default'           => 'regular', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Multiple_Control(
                $wp_customize,
                'second_font_variants',
                ['label'         => __('Weight of the second font family', 'italystrap'), 'description'   => __('Chose carefully the weight of the font family, multiple selection allowed (press CTRL and click). Performance tips: Do not load too much font weights.', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'second_font_variants', 'priority'      => 10, 'choices'       => $this->variants]
            )
        );

        /**
         * Add a textarea control for typography
         */
        $wp_customize->add_setting(
            'second_font_subsets',
            ['default'           => 'latin', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );

        $wp_customize->add_control(
            new Customize_Select_Multiple_Control(
                $wp_customize,
                'second_font_subsets',
                ['label'         => __('Subsets of the second font family', 'italystrap'), 'description'   => __('Chose the subsets of the font family, default "latin", multiple selection allowed (press CTRL and click). Performance tips: Do not load too much font subsets.', 'italystrap'), 'section'       => 'fonts', 'settings'      => 'second_font_subsets', 'priority'      => 10, 'choices'       => $this->subsets]
            )
        );

        /**
         * Select the menus_width of navbar
         */
        $wp_customize->add_setting(
            'second_typography',
            ['default'           => '', 'type'              => 'theme_mod', 'capability'        => $this->capability, 'transport'         => 'postMessage', 'sanitize_callback' => 'sanitize_text_field']
        );
        $wp_customize->add_control(
            'second_typography',
            ['settings'  => 'second_typography', 'label'         => __('Typography for the second font', 'italystrap'), 'description'   => __('Insert here one or more HTML tags or CSS selector separated by comma of the element you want to display this font. Example: <code>body</code> or <code>h1</code> or <code>h1,h2,h3,h4,h5,h6,.widget-title</code>', 'italystrap'), 'section'       => 'fonts', 'type'          => 'input']
        );
    }
}
