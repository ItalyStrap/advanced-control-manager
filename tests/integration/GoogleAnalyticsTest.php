<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration;

use ItalyStrap\Tests\IntegrationTestCase;

class GoogleAnalyticsTest extends IntegrationTestCase
{
    public function setUp(): void
    {
        // before
        parent::setUp();

        $this->config = (array) get_option('italystrap_settings');

        $this->config = wp_parse_args($this->config, \ItalyStrap\Core\get_default_from_config(require(ITALYSTRAP_PLUGIN_PATH . 'admin/config/options.php')));

        // your set up methods here
        $this->analytics = new ItalyStrap\Google\Analytics($this->config);
        $this->dom = new DOMDocument();
    }

    public function it_should_be_return_standard_script()
    {
        $this->dom->loadHTML($this->analytics->standard_script('ga();'));

        $this->assertNotEmpty($this->dom->getElementsByTagName('script'));
    }

    public function it_should_be_return_alternative_script()
    {

        $this->dom->loadHTML($this->analytics->alternative_script('ga();'));

        $this->assertNotEmpty($this->dom->getElementsByTagName('script'));
    }

    public function it_should_be_return_analytics_script()
    {

        $this->assertNotEmpty($this->analytics->standard_script($this->analytics->ga_commands_queue()));
        $this->assertNotEmpty($this->analytics->alternative_script($this->analytics->ga_commands_queue()));
    }
}
