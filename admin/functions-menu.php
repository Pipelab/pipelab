<?php
/**
 * All menu-related functions.
 *
 * @since 0.2.0
 */

add_action( 'admin_menu', 'pipelab_plugin_menus' );
/**
 * Register the plugin menus.
 *
 * @since 0.2.0
 */
function pipelab_plugin_menus() {

	// The main menu.
	add_menu_page(
		__( 'Pipelab', 'pipelab' ),
		__( 'Pipelab', 'pipelab' ),
		'view_contact',
		'pipelab',
		'pipelab_contacts_page',
		'dashicons-businessman'
	);

	// The contacts sub-menu.
	add_submenu_page(
		'pipelab',
		__( 'Contacts', 'pipelab' ),
		__( 'Contacts', 'pipelab' ),
		'view_contact',
		'pipelab-contacts',
		'pipelab_contacts_page'
	);
}

/**
 * Display the contacts list.
 *
 * @since 0.2.0
 */
function pipelab_contacts_page() {

	if ( ! current_user_can( 'view_contact' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	include( 'partials/contacts-list.php' );
}
