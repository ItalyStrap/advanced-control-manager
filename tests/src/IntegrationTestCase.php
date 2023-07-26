<?php

declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\TestCase\WPTestCase;

class IntegrationTestCase extends WPTestCase
{
	protected $tester;

	public function setUp(): void
	{
		parent::setUp();

		$this->validation = new \ItalyStrap\Update\Validation();

		// your set up methods here
	}

	public function tearDown(): void
	{
		// your tear down methods here

		// then
		parent::tearDown();
	}

}
