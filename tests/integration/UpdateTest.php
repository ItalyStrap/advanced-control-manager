<?php

class UpdateTest extends \Codeception\TestCase\WPTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->update = new ItalyStrap\Update\Update(new ItalyStrap\Update\Validation(), new ItalyStrap\Update\Sanitization());

        $this->instance = [
            'ids'      => '1,2,3',
        ];

        $this->fields = [
            'id'            => 'ids',
            'capability'    => '',
            'validate'      => 'numeric_comma',
            'sanitize'      => 'sanitize_text_field',
        ];

        // your set up methods here
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
        $this->assertInstanceOf('ItalyStrap\Update\Update', $this->update);
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instance_of_Update_Interface()
    {
        $this->assertInstanceOf('ItalyStrap\Update\Update_Interface', $this->update);
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_return_an_array_if_params_is_empty()
    {
        $is_array = $this->update->update([], []);
        $this->assertTrue(is_array($is_array));
    }

    /**
     * @test
     * it should be instantiatable
     */
    // public function it_should_be_return_an_array() {

    //     $this->instance = [
    //         'ids'      => '1,2,3',
    //     ];

    //     $this->fields = [
    //         'id'            => 'ids',
    //         'capability'    => true,
    //         'validate'      => 'numeric_comma',
    //         'sanitize'      => 'sanitize_text_field',
    //     ];

    //     $is_array = $this->update->update( $this->instance, $this->fields );
    //     $this->assertTrue( is_array( $is_array ) );
    // }

    // /**
    //  * @test
    //  * it_should_be_return_string_without_html_tags
    //  */
    // public function it_should_be_return_string_without_html_tags()
    // {
    //     $instance_value = '<h1>Test</h1><!-- Comment --><script>alert("Hack");</script>';
    //     $value = $this->update->sanitize( 'strip_tags', $instance_value );
    //     $this->assertTrue( $value === strip_tags( $instance_value ), $value );
    //     // $this->assertTrue( ! strpos( $value,'</h1>' ), $value );
    //     // $this->assertTrue( ! strpos( $value, '<h1>' ), $value );
    // }

    // /**
    //  * @test
    //  * it_should_be_return_escaped_attr
    //  */
    // public function it_should_be_return_escaped_attr()
    // {
    //     $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
    //     $value = $this->update->sanitize( 'esc_attr', $instance_value );
    //     $this->assertTrue( $value === esc_attr( $instance_value ), $value );

    // }

    // /**
    //  * @test
    //  * it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist
    //  */
    // public function it_should_be_return_sanitized_text_field_if_func_or_method_doesnt_exist()
    // {
    //     $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
    //     $value = $this->update->sanitize( 'doesnt_exist', $instance_value );
    //     $this->assertTrue( $value === sanitize_text_field( $instance_value ), $value );

    // }

    // /**
    //  * @test
    //  * it_should_be_return_empty_string
    //  */
    // public function it_should_be_return_empty_string()
    // {
    //     $instance_value = '';
    //     $value = $this->update->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
    //     $this->assertTrue( '' === $value );

    // }

    // /**
    //  * @test
    //  * it_should_be_return_an_array_if_string_is_passed
    //  */
    // public function it_should_be_return_an_array_if_string_is_passed()
    // {
    //     $instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
    //     $value = $this->update->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
    //     $this->assertTrue( is_array( $value ) );

    // }

    // /**
    //  * @test
    //  * it_should_be_return_empty_array_if_string_is_passed
    //  */
    // public function it_should_be_return_empty_array_if_string_is_passed()
    // {
    //     $instance_value = array( '<h1>Test</h1><!-- Comment --><script></script>' );
    //     $value = $this->update->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
    //     $this->assertTrue( empty( $value ) , $value );

    // }

    // /**
    //  * @test
    //  * it_should_be_return_sanitized_array
    //  */
    // public function it_should_be_return_sanitized_array()
    // {
    //     $instance_value = array( 0, 1, '<script></script>' );
    //     $value = $this->update->sanitize( 'sanitize_taxonomy_multiple_select', $instance_value );
    //     foreach ( $value as $key => $value ) {
    //         $this->assertTrue( is_int( $value ), $value );
    //     }

    // }
}
