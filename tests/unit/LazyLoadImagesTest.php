<?php

use tad\FunctionMockerLe;

class LazyLoadImagesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	/**
	 * @var \Prophecy\Prophecy\ObjectProphecy
	 */
	private $config;

	/**
	 * @return \ItalyStrap\Config\ConfigInterface
	 */
	public function getConfig(): \ItalyStrap\Config\ConfigInterface {
		return $this->config->reveal();
	}

	protected function _before()
    {

    	if ( ! defined('ITALYSTRAP_PLUGIN_PATH') ) {
			define( 'ITALYSTRAP_PLUGIN_PATH', '' );
		}

    	FunctionMockerLe\undefineAll(['is_admin']);
    	FunctionMockerLe\define('is_admin', function () {return false;});
    	FunctionMockerLe\define('add_filter', function () {return '';});

    	$this->config = $this->prophesize( \ItalyStrap\Config\ConfigInterface::class );
    }

    protected function _after()
    {
    }

	private function getInstance() {
		$sut = new \ItalyStrap\Lazyload\Image($this->getConfig(), []);
		$this->assertInstanceOf( \ItalyStrap\Lazyload\Image::class, $sut, '' );
		return $sut;
    }

	/**
	 * @test
	 */
	public function itShouldBeInstantiable() {
		$sut = $this->getInstance();
    }

	/**
	 * @test
	 */
	public function itShouldExecuteInit() {
		$sut = $this->getInstance();
		$sut->init();
    }
}
