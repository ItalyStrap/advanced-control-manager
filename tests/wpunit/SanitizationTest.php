<?php namespace ItalyStrap\Core;

use ItalyStrap\Admin\Sanitization;

class SanitizationTest extends \Codeception\TestCase\WPTestCase
{

    private $sanitization;

    public function setUp()
    {
        // before
        parent::setUp();

        $this->sanitization = new Sanitization;

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

    public function it_should_be_sanitize_return_sanitize_text()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->sanitization->sanitize( 'strip_tags|esc_attr', $instance_value );
        $this->assertTrue( ! strpos( $value, array( '<h1>', '</h1>', '<!-- Comment -->', '<script>', '</script>', ) ) );
    }

}