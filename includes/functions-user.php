<?php
/**
 * User functions.
 *
 * A collection of helper functions for interacting with users.
 *
 * @link              https://julienliabeuf.com
 * @since             0.1.0
 * @package           Pipelab
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get all the registered user roles and their capabilities.
 *
 * @since 0.1.0
 *
 * @param $role string Optional role name. If a role name is provided, capabilities for that specific role will be
 *              returned.
 *
 * @return false|array Array of roles and capabilities or false if the requested role doesn't exist.
 */
function pipelab_get_user_roles( $role = '' ) {

	// Load all the user roles.
	require( 'user-roles.php' );

	// Allow for customizing the roles and capabilities with a filter.
	$roles = apply_filters( 'pipelab_user_roles', $pipelab_user_roles );

	if ( '' === $role ) {
		return $roles;
	}

	if ( array_key_exists( $role, $roles ) ) {
		return $roles[ $role ];
	}

	return false;
}