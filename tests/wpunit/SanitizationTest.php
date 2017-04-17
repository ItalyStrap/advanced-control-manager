<?php

class SanitizationTest extends \Codeception\TestCase\WPTestCase
{

    private $sanitization;

    private $instance;

    public function setUp()
    {
        // before
        parent::setUp();

        $this->sanitization = new \ItalyStrap\Update\Sanitization;

        $this->instance = '<h1>Test</h1><!-- Comment --><script>alert("Hack");</script>';

        // your set up methods here
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable() {
        $this->assertInstanceOf( 'ItalyStrap\Update\Sanitization', $this->sanitization );
    }

    /**
     * @test
     * it_should_be_return_string_without_html_tags
     */
    public function it_should_be_return_string_without_html_tags()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script>alert("Hack");</script>';
        $value = $this->sanitization->sanitize( 'strip_tags', $instance_value );
        $this->assertTrue( $value === strip_tags( $instance_value ), $value );
        // $this->assertTrue( ! strpos( $value,'</h1>' ), $value );
        // $this->assertTrue( ! strpos( $value, '<h1>' ), $value );
    }

    /**
     * @test
     * it_should_be_return_escaped_attr
     */
    public function it_should_be_return_escaped_attr()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->sanitization->sanitize( 'esc_attr', $instance_value );
        $this->assertTrue( $value === esc_attr( $instance_value ), $value );

    }

    /**
     * @test
     * it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist
     */
    public function it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->sanitization->sanitize( 'doesnt_exist', $instance_value );
        $this->assertTrue( $value === sanitize_text_field( $instance_value ), $value );

    }

    /**
     * @test
     * it_should_be_return_empty_string
     */
    public function it_should_be_return_empty_string()
    {   
        $instance_value = '';
        $value = $this->sanitization->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
        $this->assertTrue( '' === $value );

    }

    /**
     * @test
     * it_should_be_return_an_array_if_string_is_passed
     */
    public function it_should_be_return_an_array_if_string_is_passed()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->sanitization->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
        $this->assertTrue( is_array( $value ) );

    }

    /**
     * @test
     * it_should_be_return_empty_array_if_string_is_passed
     */
    public function it_should_be_return_empty_array_if_string_is_passed()
    {   
        $instance_value = array( '<h1>Test</h1><!-- Comment --><script></script>' );
        $value = $this->sanitization->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
        $this->assertTrue( empty( $value ) , $value );

    }

    /**
     * @test
     * it_should_be_return_sanitized_array
     */
    public function it_should_be_return_sanitized_array()
    {   
        $instance_value = array( 0, 1, '<script></script>' );
        $value = $this->sanitization->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
        foreach ( $value as $key => $value ) {
            $this->assertTrue( is_int( $value ), $value );
        }

    }

    /**
     * @see WP_Widget::save_settings()
     */
    function test_wp_widget_save_settings() {
        global $wp_registered_widgets;

        wp_widgets_init();
        $wp_widget_search = $wp_registered_widgets['search-2']['callback'][0];

        $settings = $wp_widget_search->get_settings();
        $overridden_title = 'Unit Tested';

        /*
         * Note that if a plugin is filtering $settings to be an ArrayIterator,
         * then doing this:
         *     $settings[2]['title'] = $overridden_title;
         * Will fail with this:
         * > Indirect modification of overloaded element of X has no effect.
         * So this is why the value must be obtained.
         */
        $instance = $settings[2];
        $instance['title'] = $overridden_title;
        $settings[2] = $instance;

        $wp_widget_search->save_settings( $settings );

        $option_value = get_option( $wp_widget_search->option_name );
        $this->assertArrayHasKey( '_multiwidget', $option_value );
        $this->assertEquals( $overridden_title, $option_value[2]['title'] );
    }

    /**
     * @test
     * it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist2
     */
    // public function it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist2()
    // {
    //     $this->setExpectedException( 'InvalidArgumentException' );

    //     $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
    //     $value = $this->sanitization->sanitize( array(), $instance_value );
    //     // $this->assertTrue( $value === sanitize_text_field( $instance_value ), $value );

    // }

}
