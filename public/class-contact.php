<?php
namespace Pipelab;

/**
 * The class that handles Pipelab contacts.
 *
 * @link       https://julienliabeuf.com
 * @since      0.2.0
 *
 * @package    Pipelab
 * @subpackage Pipelab/includes
 */

class Contact {

	/**
	 * The contact ID.
	 *
	 * @since 0.2.0
	 * @var int
	 */
	public $contact_id;

	/**
	 * The contact object.
	 *
	 * @since 0.2.0
	 * @var object|null
	 */
	protected $contact;

	/**
	 * The user ID.
	 *
	 * @since 0.2.0
	 * @var int
	 */
	public $user_id;

	/**
	 * The user object.
	 *
	 * @since 0.2.0
	 * @var \WP_User
	 */
	protected $user;

	/**
	 * The contact first name.
	 *
	 * @since 0.2.0
	 * @var string
	 */
	protected $first_name;

	/**
	 * The contact last name.
	 *
	 * @since 02.0
	 * @var string
	 */
	protected $last_name;

	public function __construct( $contact_id = 0 ) {

		// If is a contact ID passed then we try to fetch the contact.
		if ( 0 !== (int) $contact_id ) {

			// Set the contact ID.
			$this->contact_id = (int) $contact_id;

			// Find contact by ID.
			$this->contact = $this->get_contact_by( 'ID' );

			// If the contact was found, we also fetch the user object.
			if ( null !== $this->contact ) {
				$this->user = get_user_by( 'ID', $this->contact->user_id );
				$this->user_id = $this->user->ID;
			} else {

				// Find contact by user ID if nothing was found by contact ID.
				$this->user = get_user_by( 'ID', $this->contact_id );

				if ( is_a( $this->user, 'WP_User' ) ) {

					// Keep the user ID handy.
					$this->user_id = $this->user->ID;

					// Get the contact by user ID.
					$this->contact = $this->get_contact_by( 'user_id' );
					$this->contact_id = $this->contact->ID;
				} else {
					throw new \Exception('A contact with this ID could not be found.' );
				}
			}

		}

	}

	/**
	 * Fetch the contact from the database using the specified field.
	 *
	 * @since 0.2.0
	 * @param string $field The field to use for the contact lookup.
	 * @return \stdClass|null
	 */
	protected function get_contact_by( $field = 'ID' ) {

		global $wpdb;

		if ( is_null( $this->contact_id ) ) {
			return null;
		}

		$table = $wpdb->prefix . 'pipelab_contacts';
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE $field = %s", $this->contact_id ), OBJECT );

		return $row;

	}

	/**
	 * Returns the contact base information.
	 *
	 * @since 0.2.0
	 * @return object|\stdClass|null
	 */
	public function get_contact() {
		$this->contact->first_name = $this->get_first_name();
		$this->contact->last_name = $this->get_last_name();
		return $this->contact;
	}

	/**
	 * Get the contact first name.
	 *
	 * @since 0.2.0
	 * @return mixed
	 */
	public function get_first_name() {
		if ( is_null( $this->first_name ) ) {
			$this->first_name = $this->contact->first_name;
		}

		return $this->first_name;
	}

	/**
	 * Get the contact last name.
	 *
	 * @since 0.2.0
	 * @return mixed
	 */
	public function get_last_name() {
		if ( is_null( $this->last_name ) ) {
			$this->last_name = $this->contact->last_name;
		}

		return $this->last_name;
	}

	/**
	 * Get the contact type.
	 *
	 * @since 0.2.0
	 * @return string
	 */
	public function get_type() {

		$contact_types = apply_filters( 'pipelab_contact_types', [ 'lead' => __( 'Lead', 'pipelab' ), 'client' => __( 'Client', 'pipelab' ) ] );

		return array_key_exists( $this->contact->type, $contact_types ) ? $contact_types[$this->contact->type] : '';

	}

	/**
	 * Get the contact full name.
	 *
	 * @since 0.2.0
	 * @return string
	 */
	public function get_full_name() {
		return $this->get_first_name() . ' ' . $this->get_last_name();
	}

}
