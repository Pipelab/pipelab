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
	 * Test that the main instance of the plugin is, indeed, the AuthPress class.
	 */
	function test_init() {
		$this->assertInstanceOf( Pipelab::class, pipelab() );
	}
}