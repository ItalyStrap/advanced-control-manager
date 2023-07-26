<?php

declare(strict_types=1);

namespace ItalyStrap\Test\Integration;

use ItalyStrap\Excerpt\Excerpt;
use ItalyStrap\Config\Config;
use ItalyStrap\I18N\Translator;
use ItalyStrap\Query\Posts;
use ItalyStrap\Tests\IntegrationTestCase;
use PHPUnit\Framework\Assert;
use WP_Query;

class QueryPostTest extends IntegrationTestCase
{

    private function makeInstance(): Posts
	{
		return new Posts(
			new WP_Query(),
			new Excerpt(
				new Config(),
				new Translator('ItalyStrap')
			),
			'test'
		);
    }

    public function postTypeProvider(): \Generator
	{
        yield 'default post type "post"' => [
            [],
            'post'
        ];

        yield 'post type "test"' => [
            ['post_types' => 'test'],
            'test'
        ];

        yield 'post type "test" e "post"' => [
            ['post_types' => 'test, post'],
            ['test', 'post']
        ];

        yield 'post type "post" e "test"' => [
            ['post_types' => 'post, test'],
            ['post', 'test']
        ];
    }

    /**
     * @dataProvider postTypeProvider()
     * @test
     */
    public function itShouldReturn($shortcode_args, $expected_value)
    {
        $sut = $this->makeInstance();
        $sut->get_shortcode_args($shortcode_args);
        $args = $sut->get_query_args();

        Assert::assertEquals($expected_value, $args['post_type'], '');
    }

    public function itShouldBeEchoReadMoreLink()
    {

        $sut = $this->makeInstance();

        ob_start();
        $sut->read_more_link();
        $out = ob_get_clean();

        $this->dom->loadHTML($out);
        $elements = $this->dom->getElementsByTagName('a');
        $this->assertNotEmpty($elements, 'message');

        foreach ($elements as $key => $element) {
            $this->assertNotNull($element->getAttribute('class'), 'Attribute class is empty');
            $this->assertTrue(strpos($element, 'more-link'), 'Class more-link is empty');
        }
    }
}
