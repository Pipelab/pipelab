<?php
/**
 * @package   Pipelab/Tests
 * @author    Julien Liabeuf <julien@liabeuf.fr>
 * @license   GPL-2.0+
 * @link      https://julienliabeuf.com
 * @copyright 2018 Julien Liabeuf
 */

/**
 * Sample test case.
 */
class PluginActivationTests extends WP_UnitTestCase {

	/**
	 * Test that the plugin custom user roles are present.
	 */
	function test_user_roles() {
		$this->assertInstanceOf( 'WP_Role', get_role( 'pipelab_manager' ), 'The manager role does not exist' );
		$this->assertInstanceOf( 'WP_Role', get_role( 'pipelab_sales' ), 'The sales role does not exist' );
		$this->assertInstanceOf( 'WP_Role', get_role( 'pipelab_client' ), 'The client role does not exist' );
	}

	/**
	 * Test that the admin has the right capabilities.
	 */
	function test_admin_capabilities() {

		// Create a new user and set it as admin.
		$admin = new WP_User( 1 );
		$admin->set_role( 'administrator' );

		// Check for the right capabilities.
		$this->assertTrue( $admin->has_cap( 'create_contact' ), 'The admin role does not have the custom capabilities' );
		$this->assertTrue( $admin->has_cap( 'create_account' ), 'The admin role does not have the custom capabilities' );
		$this->assertTrue( $admin->has_cap( 'create_deal' ), 'The admin role does not have the custom capabilities' );

	}

	/**
	 * Test that the agent has the right capabilities.
	 */
	function test_manager_capabilities() {

		// Create a new user and set it as agent.
		$manager = new WP_User( 1 );
		$manager->set_role( 'pipelab_manager' );

		// Check for the right capabilities.
		$this->assertTrue( $manager->has_cap( 'create_contact' ), 'The manager role does not have the custom capabilities' );
		$this->assertTrue( $manager->has_cap( 'create_account' ), 'The manager role does not have the custom capabilities' );
		$this->assertTrue( $manager->has_cap( 'create_deal' ), 'The manager role does not have the custom capabilities' );

	}

	/**
	 * Test that the sales has the right capabilities.
	 */
	function test_sales_capabilities() {

		// Create a new user and set it as ops.
		$sales = new WP_User( 1 );
		$sales->set_role( 'pipelab_sales' );

		// Check for the right capabilities.
		$this->assertTrue( $sales->has_cap( 'create_contact' ), 'The sales role does not have the custom capabilities' );
		$this->assertTrue( $sales->has_cap( 'create_account' ), 'The sales role does not have the custom capabilities' );
		$this->assertTrue( $sales->has_cap( 'create_deal' ), 'The sales role does not have the custom capabilities' );
	}

	/**
	 * Test that the client has the right capabilities.
	 */
	function test_client_capabilities() {

		// Create a new user and set it as ops.
		$client = new WP_User( 1 );
		$client->set_role( 'pipelab_client' );

		// Check for the right capabilities.
		$this->assertFalse( $client->has_cap( 'create_contact' ), 'The client role does not have the custom capabilities' );
		$this->assertFalse( $client->has_cap( 'create_account' ), 'The client role does not have the custom capabilities' );
		$this->assertTrue( $client->has_cap( 'create_deal' ), 'The client role does not have the custom capabilities' );
	}

}