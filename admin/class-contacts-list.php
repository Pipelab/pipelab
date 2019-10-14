<?php

namespace Pipelab;

use Tinify\Exception;

/**
 * The plugin contacts list class.
 *
 * @since      0.2.0
 * @package    Pipelab
 * @subpackage Pipelab/admin
 * @author     Julien Liabeuf <julien@liabeuf.fr>
 * @link       https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Contacts_List extends \WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Contact', 'pipelab' ), //singular name of the listed records
			'plural'   => __( 'Contacts', 'pipelab' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );

	}

	/**
	 * Retrieve contact’s data from the database.
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_contacts( $per_page = 25, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}pipelab_contacts";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Delete a contact record.
	 *
	 * @param int $id contact ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}pipelab_contacts",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}pipelab_contacts";

		return $wpdb->get_var( $sql );
	}

	/**
	 * Text displayed when no customer data is available
	 *
	 * @return void
	 */
	public function no_items() {
		_e( 'No contacts avaliable.', 'pipelab' );
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

		// create a nonce
		$delete_nonce = wp_create_nonce( 'sp_delete_customer' );

		$title = '<strong>' . $item['name'] . '</strong>';

		$actions = [
			'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {

		$contact = new Contact( $item['ID'] );

		switch ( $column_name ) {
			case 'first_name':
				return $contact->get_first_name();
			case 'last_name':
				return $contact->get_last_name();
			case 'type':
				return $contact->get_type();
			case 'owner':
				if ( ! is_null( $item['owner_id'] ) ) {
					try {
						$owner = new Contact( $item['owner_id'] );
						return $owner->get_first_name();
					} catch( Exception $e ) {
						// Don't display anything.
					}
				}
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes.
		}
	}

	/**
	 * Render the bulk edit checkbox.
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	/**
	 *  Associative array of columns.
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'first_name'    => __( 'First Name', 'pipelab' ),
			'last_name' => __( 'Last Name', 'pipelab' ),
			'type'    => __( 'Type', 'pipelab' ),
			'owner'    => __( 'Owner', 'pipelab' ),
		];

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'first_name' => array( 'first_name', true ),
			'last_name' => array( 'last_name', true ),
			'type' => array( 'type', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => __( 'Delete', 'pipelab' )
		];

		return $actions;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );


		$this->items = self::get_contacts( $per_page, $current_page );
	}

	/**
	 * Process the bulk actions.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_customer( absint( $_GET['customer'] ) );

				wp_redirect( esc_url( add_query_arg() ) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_customer( $id );

			}

			wp_redirect( esc_url( add_query_arg() ) );
			exit;
		}
	}
}
