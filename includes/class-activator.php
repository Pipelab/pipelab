<?php
namespace Pipelab;

/**
 * Fired during plugin activation
 *
 * @link       https://julienliabeuf.com
 * @since      0.1.0
 *
 * @package    Pipelab
 * @subpackage Pipelab/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    Pipelab
 * @subpackage Pipelab/includes
 * @author     Julien Liabeuf <julien@liabeuf.fr>
 */
class Activator {

	/**
	 * Activates the plugin.
	 *
	 * @since    0.1.0
	 */
	public static function activate() {

		// Create the new user roles.
		self::create_roles();

		// Add the new capabilities to administrators.
		self::add_admin_capabilities();

		// Create the custom database tables.
		self::create_contacts_table();
	}

	/**
	 * Create the necessary user roles.
	 *
	 * @since 0.1.0
	 */
	public static function create_roles() {

		// Add the manager role.
		add_role( 'pipelab_manager', __( 'Manager', 'pipelab' ), pipelab_get_user_roles( 'manager' ) );

		// Add the sales ops role.
		add_role( 'pipelab_sales', __( 'Sales', 'pipelab' ), pipelab_get_user_roles( 'sales' ) );

		// Add the client role.
		add_role( 'pipelab_client', __( 'Client', 'pipelab' ), pipelab_get_user_roles( 'client' ) );
	}

	/**
	 * Add all the plugin capabilities to standard admins.
	 *
	 * @since 0.1.0
	 */
	public static function add_admin_capabilities() {

		$admin              = get_role( 'administrator' );
		$admin_capabilities = pipelab_get_user_roles( 'admin' );

		if ( is_null( $admin ) ) {
			return false;
		}

		foreach ( $admin_capabilities as $capability => $permission ) {
			$admin->add_cap( $capability, $permission );
		}

	}

	/**
	 * Create the custom database tables required.
	 *
	 * @since 0.2.0
	 * @reutn void
	 */
	public static function create_database() {
		self::create_contacts_table();
	}

	/**
	 * Create the contacts table.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	protected static function create_contacts_table() {

		global $wpdb;

		$table = $wpdb->prefix . 'pipelab_contacts';

		/* Prepare DB structure if not already existing */
		if ( $wpdb->get_var( "show tables like '$table'" ) != $table ) {
			$sql = "CREATE TABLE $table (
				contact_id mediumint(9) NOT NULL AUTO_INCREMENT,
				owner_id mediumint(9),
				gender VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
				type VARCHAR(20) COLLATE utf8_general_ci NOT NULL,
				job_title VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				mobile_number VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				address VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				city VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				country VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				zipcode VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				source VARCHAR(256) DEFAULT '' COLLATE utf8_general_ci NOT NULL,
				created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				modified_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				UNIQUE KEY contact_id (contact_id)
				);";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

}
