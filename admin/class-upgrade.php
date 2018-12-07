<?php

namespace Pipelab;

/**
 * The plugin upgrade routines class.
 *
 * @since      0.2.0
 * @package    Pipelab
 * @subpackage Pipelab/admin
 * @author     Julien Liabeuf <julien@liabeuf.fr>
 */
class Upgrade {

	/**
	 * Version number stored in the database.
	 *
	 * @since 0.2.0
	 * @var $installed_version string
	 */
	public $installed_version = '';

	/**
	 * Version number declared in the plugin main file.
	 *
	 * @since 0.2.0
	 * @var $latest_version string
	 */
	public $latest_version = '';

	/**
	 * Database version currently installed.
	 *
	 * @since 0.2.0
	 * @var string
	 */
	public $installed_db_version = '';

	/**
	 * Latest database version (as declared in the plugin files).
	 *
	 * @since 0.2.0
	 * @var string
	 */
	public $latest_db_version = '';

	/**
	 * Type of routine required.
	 *
	 * @since 0.2.0
	 * @var $routine string
	 */
	public $routine = '';

	public function __construct() {
		$this->installed_version    = get_option( 'pipelab_version', '0.0.1' );
		$this->latest_version       = PIPELAB_VERSION;
		$this->installed_db_version = get_option( 'pipelab_db_version', '0' );
		$this->latest_db_version    = PIPELAB_DB_VERSION;
	}

	public function maybe_upgrade() {

		if ( true === $this->needs_upgrade() ) {

			// Load our upgrade functions.
			require_once( 'functions-upgrade.php' );

			// Find out if we're upgrading or downgrading.
			$this->routine = $this->which_routine();

			// Run the from-to upgrade routine.
			$this->upgrade_from_to();

			// Run the regular upgrade routine.
			$this->upgrade_current();

			// Update the version numbers saved in the database.
			$this->update_db_version();
		}

	}

	/**
	 * Check if the plugin needs an upgrade.
	 *
	 * @since 0.2.0
	 * @return bool
	 */
	public function needs_upgrade() {
		return $this->installed_version !== $this->latest_version ? true : false;
	}

	/**
	 * Check whether we are upgrading or downgrading the plugin.
	 *
	 * @since 0.2.0
	 * @return string
	 */
	public function which_routine() {
		return version_compare( $this->installed_version, $this->latest_version, '<' ) ? 'upgrade' : 'downgrade';
	}

	/**
	 * Update the version number in the database.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	protected function update_db_version() {
		update_option( 'pipelab_version', $this->latest_version );
		update_option( 'pipelab_db_version', $this->latest_db_version );
	}

	/**
	 * Upgrade from a specific version to the current one.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	protected function upgrade_from_to() {

		$from          = str_replace( '.', '', $this->installed_version );
		$to            = str_replace( '.', '', $this->latest_version );
		$function_name = "pipelab_upgrade_{$from}_{$to}";

		if ( function_exists( $function_name ) ) {
			call_user_func( $function_name );  // We have a very specific routine for this from/to combination.
		}

	}

	/**
	 * Update the current version whatever the previous one was
	 *
	 * @since 0.2.0
	 * @return void
	 */
	protected function upgrade_current() {

		$version       = str_replace( '.', '', $this->latest_version );
		$function_name = "pipelab_upgrade_{$version}";

		if ( function_exists( $function_name ) ) {
			call_user_func( $function_name );
		}

	}

}