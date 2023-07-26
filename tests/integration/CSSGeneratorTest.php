<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration;

use ItalyStrap\Tests\IntegrationTestCase;
use ItalyStrap\Widgets\Areas\CSS_Generator;
use ReflectionClass;

class CSSGeneratorTest extends IntegrationTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->style = [
            'id'    => 'test',
            'style' => [
                'background-color'  => '#d3bee2',
                'background-image'  => 0,
            ],
        ];
    }

	private function makeInstance(): CSS_Generator
	{
		return new CSS_Generator();
	}

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('ItalyStrap\Widgets\Areas\CSS_Generator');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
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
     * @dataProvider  css_invalid_value_provider
     */
    public function it_should_return_false_of_the_value_is_valid($value)
    {
        $foo = self::getMethod('is_not_empty_value');
        $obj = new \ItalyStrap\Widgets\Areas\CSS_Generator();
        $this->assertFalse($foo->invokeArgs($obj, [ $value ]), "Value $value is not false");
    }
}
