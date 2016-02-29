<?php

if ( is_multisite() ) :

/**
 * Tests specific to network options in Multisite.
 *
 * @group option
 * @group ms-option
 * @group multisite
 */
class Tests_Option_NetworkOption extends WP_UnitTestCase {
	function test_add_network_option_not_available_on_other_network() {
		$id = self::factory()->network->create();
		$option = rand_str();
		$value = rand_str();

		add_site_option( $option, $value );
		$this->assertFalse( get_network_option( $id, $option, false ) );
	}

	function test_add_network_option_available_on_same_network() {
		$id = self::factory()->network->create();
		$option = rand_str();
		$value = rand_str();

		add_network_option( $id, $option, $value );
		$this->assertEquals( $value, get_network_option( $id, $option, false ) );
	}

	function test_delete_network_option_on_only_one_network() {
		$id = self::factory()->network->create();
		$option = rand_str();
		$value = rand_str();

		add_site_option( $option, $value );
		add_network_option( $id, $option, $value );
		delete_site_option( $option );
		$this->assertEquals( $value, get_network_option( $id, $option, false ) );
	}

	/**
	 * @dataProvider data_network_id_parameter
	 *
	 * @param $network_id
	 * @param $expected_response
	 */
	function test_add_network_option_network_id_parameter( $network_id, $expected_response ) {
		$option = rand_str();
		$value = rand_str();

		$this->assertEquals( $expected_response, add_network_option( $network_id, $option, $value ) );
	}

	/**
	 * @dataProvider data_network_id_parameter
	 *
	 * @param $network_id
	 * @param $expected_response
	 */
	function test_get_network_option_network_id_parameter( $network_id, $expected_response ) {
		$option = rand_str();

		$this->assertEquals( $expected_response, get_network_option( $network_id, $option, true ) );
	}

	function data_network_id_parameter() {
		return array(
			// Numeric values should always be accepted.
			array( 1,   true ),
			array( '1', true ),
			array( 2,   true ),

			// Null, false, and zero will be treated as the current network.
			array( null,  true ),
			array( false, true ),
			array( 0,     true ),
			array( '0',   true ),

			// Other truthy or string values should be rejected.
			array( true,     false ),
			array( 'string', false ),
		);
	}
}

endif;