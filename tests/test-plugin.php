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
class PluginTests extends WP_UnitTestCase {

	/**
	 * Test that the main instance of the plugin is, indeed, the Pipelab class.
	 */
	function test_init() {
		$this->assertInstanceOf( Pipelab::class, pipelab() );
	}

	/**
	 * Test that the WordPress version is supported. We only test the plugin in supported environments so the test should pass.
	 */
	function test_wordpress_version() {
		$pipelab = new Pipelab();
		$this->assertTrue( $pipelab->is_wordpress_version_compatible() );
	}

	/**
	 * Test that the PHP version is supported. We only test the plugin in supported environments so the test should pass.
	 */
	function test_php_version() {
		$pipelab = new Pipelab();
		$this->assertTrue( $pipelab->is_php_version_compatible() );
	}

	/**
	 * Test that the plugin has all the elements to load.
	 */
	function test_plugin_can_load() {
		$pipelab = new Pipelab();
		$this->assertTrue( $pipelab->can_load() );
	}
}