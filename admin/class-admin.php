<?php
namespace Pipelab;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://julienliabeuf.com
 * @since      0.1.0
 *
 * @package    Pipelab
 * @subpackage Pipelab/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pipelab
 * @subpackage Pipelab/admin
 * @author     Julien Liabeuf <julien@liabeuf.fr>
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Contacts_List object instance.
	 *
	 * @since 0.2.0
	 * @var Contacts_List
	 */
	public $contacts_list;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pipelab_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pipelab_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pipelab-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pipelab_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pipelab_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pipelab-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Load plugin pages and screen options.
	 *
	 * @since 0.2.0
	 * @retun void
	 */
	public function load_plugin_pages() {
		add_filter( 'set-screen-option', [ $this, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	/**
	 * Set screen options.
	 *
	 * @since 0.2.0
	 * @param $status
	 * @param $option
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Register the plugin menus.
	 *
	 * @since 0.2.0
	 * @return void
	 */
	public function plugin_menu() {

		$hook = add_menu_page(
			'Sitepoint WP_List_Table Example',
			'SP WP_List_Table',
			'manage_options',
			'wp_list_table_class',
			'pipelab_contacts_page'
		);

		add_action( "load-$hook", [ $this, 'contacts_list_screen_option' ] );

	}

	/**
	 * Screen options for the contacts list.
	 *
	 * This is loaded via a hook defined in Admin::plugin_menu().
	 *
	 * @since 0.2.0
	 */
	public function contacts_list_screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => __( 'Contacts', 'pipelab' ),
			'default' => 25,
			'option'  => 'contacts_per_page'
		];

		add_screen_option( $option, $args );

		$this->contacts_list = new Contacts_List();
	}

}
