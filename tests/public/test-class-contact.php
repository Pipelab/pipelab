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
class ContactTests extends WP_UnitTestCase {

	public $user_id;
	public $contact_id;

	public function setUp() {

		global $wpdb;

		$this->user_id = $this->factory->user->create();
		$this->contact_id = $wpdb->insert(
			$wpdb->prefix . 'pipelab_contacts',
			array(
				'user_id' => $this->user_id,
				'gender' => 'male',
				'type' => 'lead',
				'job_title' => 'CEO',
				'mobile_number' => '0123456789',
				'address' => 'My Street',
				'city' => 'Bangkok',
				'country' => 'Thailand',
				'zipcode' => '10110',
				'source' => 'test'
			)
		);
	}

	/**
	 * Test that all the contact information is correct when fetching the contact by contact ID.
	 */
	public function test_get_contact_by_id() {
		$contact = new \Pipelab\Contact( $this->contact_id );
		$this->assertInstanceOf( 'stdClass', $contact );
		$this->assertSame( $contact->get_contact()->user_id, $this->user_id );
		$this->assertSame( $contact->get_contact()->gender, 'male' );
		$this->assertSame( $contact->get_contact()->type, 'lead' );
		$this->assertSame( $contact->get_contact()->job_title, 'CEO' );
		$this->assertSame( $contact->get_contact()->mobile_number, '0123456789' );
		$this->assertSame( $contact->get_contact()->address, 'My Street' );
		$this->assertSame( $contact->get_contact()->city, 'Bangkok' );
		$this->assertSame( $contact->get_contact()->country, 'Thailand' );
		$this->assertSame( $contact->get_contact()->zipcode, '10110' );
		$this->assertSame( $contact->get_contact()->source, 'test' );
	}

}