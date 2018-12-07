<?php
/**
 * @package   Pipelab/Tests/Admin/Upgrade
 * @author    Julien Liabeuf <julien@liabeuf.fr>
 * @license   GPL-2.0+
 * @link      https://julienliabeuf.com
 * @copyright 2018 Julien Liabeuf
 */

/**
 * Sample test case.
 */
class UpgradeTests extends WP_UnitTestCase {

	public function setUp() {
		// The class is not loaded by default as it's only called in the WordPress admin.
		require_once( PIPELAB_PATH . 'admin/class-upgrade.php' );
	}

	/**
	 * Test that the upgrade routine is correctly identified when the installed version is older than the latest
	 * version of the plugin.
	 */
	public function test_needs_upgrade() {
		update_option( 'pipelab_version', '0.0.0' );
		$this->upgrade = new Pipelab\Upgrade();
		$this->assertTrue( $this->upgrade->needs_upgrade() );
	}

	/**
	 * Test that the upgrade routine is not identified when the installed version is more recent than the latest
	 * version of the plugin.
	 */
	public function test_does_not_need_upgrade() {
		update_option( 'pipelab_version', PIPELAB_VERSION );
		$this->upgrade = new Pipelab\Upgrade();

		$this->assertFalse( $this->upgrade->needs_upgrade() );

	}

	/**
	 * Test that the routine is an upgrade when the latest version is more recent than the one installed.
	 */
	public function test_upgrade_routine() {
		update_option( 'pipelab_version', '0.0.1' );
		$this->upgrade = new Pipelab\Upgrade();

		$this->assertSame( 'upgrade', $this->upgrade->which_routine() );
	}

	/**
	 * Test that the routine is a downgrade when the latest version is older than the one installed.
	 */
	public function test_downgrade_routing() {
		update_option( 'pipelab_version', '10.0.0' );
		$this->upgrade = new Pipelab\Upgrade();

		$this->assertSame( 'downgrade', $this->upgrade->which_routine() );
	}

}