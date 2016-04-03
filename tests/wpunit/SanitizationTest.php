<?php

use ItalyStrap\Admin\Sanitization;

class SanitizationTest extends \Codeception\TestCase\WPTestCase
{

    private $sanitization;

    private $instance;

    public function setUp()
    {
        // before
        parent::setUp();

        $this->sanitization = new Sanitization;

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
        $this->assertInstanceOf( 'ItalyStrap\Admin\Sanitization', $this->sanitization );
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
     * it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist2
     */
    public function it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist2()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->sanitization->sanitize( array(), $instance_value );
        $this->assertTrue( $value === sanitize_text_field( $instance_value ), $value );

    }

}