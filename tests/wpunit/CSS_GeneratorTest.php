<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

/**
 * Test private method link
 * https://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit?rq=1
 */

use ItalyStrap\Widgets\Areas\CSS_Generator;
use ReflectionClass;

class CSS_GeneratorTest extends \Codeception\TestCase\WPTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        // your set up methods here
        $this->css = new CSS_Generator();

        $this->style = [
            'id'    => 'test',
            'style' => [
                'background-color'  => '#d3bee2',
                'background-image'  => 0,
            ],
        ];
    }

    public function tearDown(): void
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('ItalyStrap\Widgets\Areas\CSS_Generator');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $this->assertInstanceOf('ItalyStrap\Widgets\Areas\CSS_Generator', $this->css);
    }

    /**
     * @test
     * it_should_be_instance_of_I_Fields
     */
    // public function it_should_be_instance_of_I_Fields() {
    //     $this->assertInstanceOf( 'ItalyStrap\Fields\Fields_Interface', $this->css );
    // }

    /**
     * @test
     * it_should_be_an_object
     */
    public function it_should_be_an_object()
    {
        $this->assertTrue(is_object($this->css));
    }

    public function css_valid_value_provider()
    {
        return [
            ['0.1'], // opacity
            ['100%'], // example: witdh: 100%
            ['1'], // opacity or thumb ID
            [1],
            ['#d3bee2'], // color
            ['#000'], // color
            ['#000000'], // color
            // ['http:'], // src
        ];
    }

    /**
     * @test
     * it_should_return_true_if_the_value_is_valid
     * @dataProvider  css_valid_value_provider
     */
    public function it_should_return_true_if_the_value_is_valid($value)
    {
        $foo = self::getMethod('is_not_empty_value');
        $obj = new \ItalyStrap\Widgets\Areas\CSS_Generator();
        $this->assertTrue($foo->invokeArgs($obj, [ $value ]), "Value $value is not true");
    }

    public function css_invalid_value_provider()
    {
        return [
            [''],
            ['#'],
            ['#0'],
            ['0'],
            [0],
            [ false ],
            [ null ],
        ];
    }

    /**
     * @test
     * it_should_return_false_of_the_value_is_valid
     * @dataProvider  css_invalid_value_provider
     */
    public function it_should_return_false_of_the_value_is_valid($value)
    {
        $foo = self::getMethod('is_not_empty_value');
        $obj = new \ItalyStrap\Widgets\Areas\CSS_Generator();
        $this->assertFalse($foo->invokeArgs($obj, [ $value ]), "Value $value is not false");
    }
}
