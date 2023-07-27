<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration;

use ItalyStrap\Tests\IntegrationTestCase;

class GeneralFunctionsTest extends IntegrationTestCase
{
    public function test_shortcode_atts_multidimensional_array()
    {

        $atts = ['title' => 'The title'];

        $array = \ItalyStrap\Core\shortcode_atts_multidimensional_array(require(ITALYSTRAP_PLUGIN_PATH . 'config/media-carousel.php'), $atts, $shortcode = '');

        $this->assertTrue(is_array($array));
    }

    public function test_file_get_content()
    {

        $unveilpath = ITALYSTRAP_PLUGIN_PATH . 'js/unveil.min.js';

        $get_file_content = \ItalyStrap\Core\get_file_content($unveilpath);

        $this->assertTrue(isset($get_file_content));
    }

    public function test_if_get_taxonomies_list_array_return_an_array()
    {

        $is_array = \ItalyStrap\Core\get_taxonomies_list_array('category');

        $this->assertTrue(is_array($is_array));
    }

    public function test_if_get_image_size_array_return_an_array()
    {

        $is_array = \ItalyStrap\Core\get_image_size_array();

        $this->assertTrue(is_array($is_array));
    }

    public function test_if_return_a_string()
    {

        $string = \ItalyStrap\Core\render_html_in_title_output('Questo è un {{strong}}titolo{{/strong}} in grassetto');

        $this->assertTrue(is_string($string));
        $this->assertStringContainsString('<strong>', $string);
        $this->assertStringContainsString('</strong>', $string);
    }

    public function test_if_return_an_object()
    {

        $detect = '';
        $detect = apply_filters('mobile_detect', $detect);

        // Then use add_filter to append object.
        add_filter('mobile_detect', 'ItalyStrap\Core\new_mobile_detect');

        $this->assertTrue(is_object($detect));
    }
}
