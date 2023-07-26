<?php

class GoogleFontTest extends \Codeception\TestCase\WPTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->config = new ItalyStrap\Config\Config();
        $this->fonts = new ItalyStrap\Google\Fonts($this->config);
        // $this->dom = new \DOMDocument();
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
        $this->assertInstanceOf('ItalyStrap\Google\Fonts', $this->fonts);
    }
}
