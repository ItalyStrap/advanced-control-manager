<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration;

use ItalyStrap\Update\Validation;

class ValidationTest extends \Codeception\TestCase\WPTestCase
{

	private function makeInstance(): Validation
	{
		return new Validation();
	}

	public function it_should_be_validate_return_true(): void
	{
		$sut = $this->makeInstance();
		$instance_value = '<h1>Test</h1><!-- Comment --><script></script>';
		$value = $sut->validate('alpha_dash', $instance_value);
		$value = $instance_value;
		$this->assertTrue(preg_match('/^[a-z0-9-_]+$/', $value));
	}
}
