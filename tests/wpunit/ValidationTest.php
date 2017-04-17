<?php

class ValidationTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp()
    {
        // before
        parent::setUp();

        $this->validation = new \ItalyStrap\Update\Validation;

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
        $this->assertInstanceOf( 'ItalyStrap\Update\Validation', $this->validation );
    }

    public function it_should_be_validate_return_true()
    {   
        $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
        $value = $this->validation->validate( 'alpha_dash', $instance_value );
        $value = $instance_value;
        $this->assertTrue( preg_match( '/^[a-z0-9-_]+$/', $value ) );
    }

}