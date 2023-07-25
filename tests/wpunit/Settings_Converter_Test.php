<?php

class Settings_Converter_Test extends \Codeception\TestCase\WPTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->settings_converter = new \ItalyStrap\Migrations\Settings_Converter();
    }

    public function tearDown(): void
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $this->assertInstanceOf('\ItalyStrap\Migrations\Settings_Converter', $this->settings_converter);
    }

    /**
     * @test
     * it_should_be_converted_to_theme_mod
     */
    public function it_should_be_converted_to_theme_mod()
    {
        $pattern = array(
            // 'old'    => 'new',
            'default_404'   => 'default_404',
            'default_image' => 'default_image',
            'logo'          => 'logo',
        );

        /**
         * The img will be converted to ID
         */
        $old_data = array(
            'default_404'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg',
            'default_image'    => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/unicorn-wallpaper.jpg',
            'logo'             => 'http://192.168.1.10/italystrap/wp-content/uploads/2015/08/26-wordpress-512.png',
        );

        $this->settings_converter->data_to_theme_mod($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(null !== get_theme_mod($value), get_theme_mod($value));
        }
    }

    /**
     * @test
     * it_should_be_converted_to_option
     */
    public function it_should_be_converted_to_option()
    {
        $pattern = array(
            'favicon'       => 'site_icon', // But this is an option
        );

        /**
         * The img will be converted to ID
         */
        $old_data = array(
            'favicon'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg',
            // 'analytics'    => 'UA-12345-6',
        );

        $this->settings_converter->data_to_option($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(null !== get_option($value), get_option($value));
        }
    }

    /**
     * @test
     * it_should_return_integer
     */
    public function it_should_return_integer()
    {
        $pattern = array(
            'favicon'       => 'site_icon', // But this is an option
        );

        /**
         * The img will be converted to ID
         */
        $old_data = array(
            'favicon'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg',
        );

        $this->settings_converter->data_to_option($pattern, $old_data);
        $this->settings_converter->data_to_theme_mod($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(is_numeric(get_option($value)), 'Value: ' . get_option($value));
            $this->assertTrue(is_numeric(get_theme_mod($value)), 'Value: ' . get_theme_mod($value));
        }
    }

    /**
     * @test
     * it_should_be_converted_to_options
     */
    public function it_should_be_converted_to_options()
    {
        $pattern = array(
            'analytics'     => 'google_analytics_id', // Option to new options
        );

        /**
         * The img will be converted to ID
         */
        $old_data = array(
            'analytics'    => 'UA-12345-6',
        );

        $new_options = get_option('italystrap_settings');

        $this->settings_converter->data_to_options($pattern, $old_data, $new_options, 'italystrap_settings');

        $options = get_option('italystrap_settings');

        foreach ($pattern as $key => $value) {
            $this->assertEquals($old_data[ $key ], $options[ $value ], $options[ $value ]);
        }
    }
}
