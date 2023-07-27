<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration;

use ItalyStrap\Migrations\SettingsMigration;
use ItalyStrap\Tests\IntegrationTestCase;

class SettingsConverterTest extends IntegrationTestCase
{
    private function makeInstance(): SettingsMigration
    {
        return new SettingsMigration();
    }

    /**
     * @test
     */
    public function it_should_be_converted_to_theme_mod()
    {
        $pattern = [
            // 'old'    => 'new',
            'default_404'   => 'default_404',
            'default_image' => 'default_image',
            'logo'          => 'logo',
        ];

        /**
         * The img will be converted to ID
         */
        $old_data = ['default_404'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg', 'default_image'    => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/unicorn-wallpaper.jpg', 'logo'             => 'http://192.168.1.10/italystrap/wp-content/uploads/2015/08/26-wordpress-512.png'];

        $this->makeInstance()->dataToThemeMod($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(null !== get_theme_mod($value), get_theme_mod($value));
        }
    }

    /**
     * @test
     */
    public function it_should_be_converted_to_option()
    {
        $pattern = ['favicon'       => 'site_icon'];

        /**
         * The img will be converted to ID
         */
        $old_data = ['favicon'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg'];

        $this->makeInstance()->dataToOption($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(null !== get_option($value), get_option($value));
        }
    }

    /**
     * @test
     */
    public function it_should_return_integer()
    {
        $pattern = ['favicon'       => 'site_icon'];

        /**
         * The img will be converted to ID
         */
        $old_data = ['favicon'      => 'http://192.168.1.10/italystrap/wp-content/uploads/2013/03/featured-image-horizontal.jpg'];

        $this->makeInstance()->dataToOption($pattern, $old_data);
        $this->makeInstance()->dataToThemeMod($pattern, $old_data);

        foreach ($pattern as $key => $value) {
            $this->assertTrue(is_numeric(get_option($value)), 'Value: ' . get_option($value));
            $this->assertTrue(is_numeric(get_theme_mod($value)), 'Value: ' . get_theme_mod($value));
        }
    }

    /**
     * @test
     */
    public function it_should_be_converted_to_options()
    {
        $pattern = ['analytics'     => 'google_analytics_id'];

        /**
         * The img will be converted to ID
         */
        $old_data = ['analytics'    => 'UA-12345-6'];

        $new_options = get_option('italystrap_settings');

        $this->makeInstance()->dataToOptions($pattern, $old_data, $new_options, 'italystrap_settings');

        $options = get_option('italystrap_settings');

        foreach ($pattern as $key => $value) {
            $this->assertEquals($old_data[ $key ], $options[ $value ], $options[ $value ]);
        }
    }
}
