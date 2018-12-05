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

}
