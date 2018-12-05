<?php
/**
 * User roles.
 *
 * This file lists all the available user roles and their capabilities.
 *
 * @link              https://julienliabeuf.com
 * @since             0.1.0
 * @package           Pipelab
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$pipelab_user_roles = array(
	// Admin roles. The permissions in here need to be added to the default administrator role.
	'admin' => array(
		// Contacts permissions.
		'create_contact'           => true,
		'edit_contact'             => true,
		'edit_others_contacts'     => true,
		'delete_contact'           => true,
		'delete_others_contacts'   => true,
		'view_contact'             => true,
		'view_private_contacts'    => true,
		// Accounts permissions.
		'create_account'          => true,
		'edit_account'            => true,
		'edit_others_accounts'    => true,
		'delete_account'          => true,
		'delete_others_accounts'  => true,
		'view_account'            => true,
		'view_private_accounts'   => true,
		// Deals permissions.
		'create_deal'              => true,
		'edit_deal'                => true,
		'edit_others_deals'        => true,
		'delete_deal'              => true,
		'delete_others_deals'      => true,
		'view_deal'                => true,
		'view_private_deals'       => true,
	),
	// Manager role.
	'manager' => array(
		// Contacts permissions.
		'create_contact'           => true,
		'edit_contact'             => true,
		'edit_others_contacts'     => true,
		'delete_contact'           => true,
		'delete_others_contacts'   => false,
		'view_contact'             => true,
		'view_private_contacts'    => true,
		// Accounts permissions.
		'create_account'          => true,
		'edit_account'            => true,
		'edit_others_accounts'    => true,
		'delete_account'          => true,
		'delete_others_accounts'  => false,
		'view_account'            => true,
		'view_private_accounts'   => true,
		// Deals permissions.
		'create_deal'              => true,
		'edit_deal'                => true,
		'edit_others_deals'        => true,
		'delete_deal'              => true,
		'delete_others_deals'      => false,
		'view_deal'                => true,
		'view_private_deals'       => true,
	),
	// Sales role.
	'sales'   => array(
		// Contacts permissions.
		'create_contact'           => true,
		'edit_contact'             => true,
		'edit_others_contacts'     => false,
		'delete_contact'           => false,
		'delete_others_contacts'   => false,
		'view_contact'             => true,
		'view_private_contacts'    => true,
		// Accounts permissions.
		'create_account'          => true,
		'edit_account'            => true,
		'edit_others_accounts'    => false,
		'delete_account'          => false,
		'delete_others_accounts'  => false,
		'view_account'            => true,
		'view_private_accounts'   => true,
		// Deals permissions.
		'create_deal'              => true,
		'edit_deal'                => true,
		'edit_others_deals'        => false,
		'delete_deal'              => true,
		'delete_others_deals'      => false,
		'view_deal'                => true,
		'view_private_deals'       => true,
	),
	// Client role.
	'client'   => array(
		// Contacts permissions.
		'create_contact'           => false,
		'edit_contact'             => false,
		'edit_others_contacts'     => false,
		'delete_contact'           => false,
		'delete_others_contacts'   => false,
		'view_contact'             => false,
		'view_private_contacts'    => false,
		// Accounts permissions.
		'create_account'          => false,
		'edit_account'            => false,
		'edit_others_accounts'    => false,
		'delete_account'          => false,
		'delete_others_accounts'  => false,
		'view_account'            => false,
		'view_private_accounts'   => false,
		// Deals permissions.
		'create_deal'              => true,
		'edit_deal'                => false,
		'edit_others_deals'        => false,
		'delete_deal'              => false,
		'delete_others_deals'      => false,
		'view_deal'                => false,
		'view_private_deals'       => false,
	),
);