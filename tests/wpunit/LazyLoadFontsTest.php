<?php

use ItalyStrap\Event\Subscriber_Interface;
use ItalyStrap\Google\Fonts as Google_Fonts;

class LazyLoadFontsTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp()
    {
        // before
        parent::setUp();

        $this->config = new ItalyStrap\Config\Config();
        $this->google_fonts = new Google_Fonts( $this->config );
        $this->lazyload_fonts = new ItalyStrap\Lazyload\Fonts( $this->google_fonts, $this->config );
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
        $this->assertInstanceOf( 'ItalyStrap\Lazyload\Fonts', $this->lazyload_fonts );
    }

}