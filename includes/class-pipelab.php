<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://julienliabeuf.com
 * @since      0.1.0
 *
 * @package    Pipelab
 * @subpackage Pipelab/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Pipelab
 * @subpackage Pipelab/includes
 * @author     Julien Liabeuf <julien@liabeuf.fr>
 */
class Pipelab {

	/**
	 * Holds the unique instance of the plugin.
	 *
	 * @var Pipelab Holds the unique instance of AuthPress.
	 * @since 0.1.0
	 */
	private static $instance;


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Pipelab\Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Possible error message.
	 *
	 * @since 0.1.0
	 * @var null|WP_Error
	 */
	protected $error = null;

	/**
	 * Minimum version of WordPress required ot run the plugin
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public $wordpress_version_required = '4.8';

	/**
	 * Required version of PHP.
	 *
	 * Follow WordPress latest requirements and require
	 * PHP version 5.2 at least.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public $php_version_required = '5.6';

	/**
	 * Instantiate and return the unique AuthPress object.
	 *
	 * @since  0.1.0
	 * @return Pipelab
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pipelab ) ) {
			self::$instance = new Pipelab;
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	private function init() {

		if ( defined( 'PIPELAB_VERSION' ) ) {
			$this->version = PIPELAB_VERSION;
		} else {
			$this->version = '0.1.0';
		}

		$this->plugin_name = 'pipelab';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();

		if ( is_admin() ) {

			$this->load_dependencies_admin();
			$this->define_admin_hooks();

		}

		// Before running the plugin, we make sure that the plugin can be safely loaded.
		// If it can't, we abort.
		if ( false === $this->can_load() ) {

			// If we have any error, let's display them.
			if ( is_a( self::$instance->error, 'WP_Error' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'display_error' ), 10, 0 );
			}

			return;
		}

		// Run the plugin loader.
		$this->loader->run();

	}

	/**
	 * Run a series of checks to confirm that the plugin can be loaded.
	 *
	 * This plugin requires specific WordPress and PHP versions as well as a number of dependencies. This method checks
	 * for all the versions and dependencies requirements and returns the final verdict: is everything the plugin needs
	 * present or not?
	 *
	 * In addition to running the checks, the method also register error notifications to inform the user of what
	 * requirements aren't fulfilled.
	 *
	 * @since 0.2.0
	 * @return bool Returns true if the plugin can be loaded, false otherwise.
	 */
	public function can_load() {

		$can_load = true;

		// Make sure that the plugin.php file is loaded. It is where is_plugin_active() lives.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		// Make sure the WordPress version is recent enough.
		if ( false === self::$instance->is_wordpress_version_compatible() ) {
			self::$instance->add_error( sprintf( __( 'Pipelab requires WordPress version %s or above. Please update WordPress to run this plugin.', 'pipelab' ), self::$instance->wordpress_version_required ) );
			$can_load = false;
		}

		// Make sure the PHP version is recent enough.
		if ( false === self::$instance->is_php_version_compatible() ) {
			self::$instance->add_error( sprintf( __( 'Pipelab requires PHP version %s or above. Read more information about <a %s>how you can update</a>.', 'pipelab' ), self::$instance->php_version_required, 'a href="http://www.wpupdatephp.com/update/" target="_blank"' ) );
			$can_load = false;
		}

		return $can_load;

	}

	/**
	 * Check if the core version is compatible with this addon.
	 *
	 * @since  0.1.0
	 * @return boolean
	 */
	public function is_wordpress_version_compatible() {

		if ( empty( self::$instance->wordpress_version_required ) ) {
			return true;
		}

		if ( version_compare( get_bloginfo( 'version' ), self::$instance->wordpress_version_required, '<' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the version of PHP is compatible with this addon.
	 *
	 * @since  0.1.0
	 * @return boolean
	 */
	public function is_php_version_compatible() {

		/**
		 * No version set, we assume everything is fine.
		 */
		if ( empty( self::$instance->php_version_required ) ) {
			return true;
		}

		if ( version_compare( phpversion(), self::$instance->php_version_required, '<' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add error.
	 *
	 * Add a new error to the WP_Error object
	 * and create the object if it doesn't exist yet.
	 *
	 * @since  0.1.0
	 *
	 * @param string $message Error message to add
	 *
	 * @return void
	 */
	private function add_error( $message ) {
		if ( ! is_object( $this->error ) || ! is_a( $this->error, 'WP_Error' ) ) {
			$this->error = new WP_Error();
		}
		$this->error->add( 'addon_error', $message );
	}

	/**
	 * Display error.
	 *
	 * Get all the error messages and display them
	 * in the admin notices.
	 *
	 * @since  0.1.0
	 * @return void
	 */
	public function display_error() {

		if ( ! is_a( $this->error, 'WP_Error' ) ) {
			return;
		}

		$message = self::$instance->error->get_error_messages(); ?>

		<div class="error">
			<p>
				<?php
				if ( count( $message ) > 1 ) {
					echo '<ul>';
					foreach ( $message as $msg ) {
						echo "<li>$msg</li>";
					}
					echo '</li>';
				} else {
					echo $message[0];
				}
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pipelab_Loader. Orchestrates the hooks of the plugin.
	 * - Pipelab_i18n. Defines internationalization functionality.
	 * - Pipelab_Admin. Defines all hooks for the admin area.
	 * - Pipelab_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';

		/**
		 * Get the contact class.
		 *
		 * @since 0.2.0
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-contact.php';

		/**
		 * A collection of helper functions for interacting with users.
		 */
		require_once 'functions-user.php';

		$this->loader = new Pipelab\Loader();

	}

	/**
	 * Load the required dependencies for the admin.
	 *
	 * We only load the following dependencies in the administrative part of WordPress in order to avoid overloading
	 * the frontend with unused code.
	 *
	 * @since    0.2.0
	 * @access   private
	 */
	private function load_dependencies_admin() {

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		/**
		 * The class responsible for the upgrade routines.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-upgrade.php';

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {

			// Register the various actions and filters used throughout the admin.
			$this->loader->add_action( 'plugins_loaded', new Pipelab\Upgrade(), 'maybe_upgrade', 11, 0 );
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pipelab_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pipelab\i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pipelab\Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pipelab\Pipelab_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Pipelab\Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
