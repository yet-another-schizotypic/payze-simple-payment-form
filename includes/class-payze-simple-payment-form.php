<?php
/*
 * Copyright (c) 2022.
 * This code was made by copy-paste and some monkey typing.
 *
 * The most significant parts are taken from the «Now Hiring» plugin by slushman
 *  (https://github.com/slushman/now-hiring), the «WordPress Boilerplate» by
 *  DevinVinson (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
 *  and the «Authorize.net - Simple Donations» by Aman Verma (https://twitter.com/amanverma217).
 *
 * License: GPLv2 or later.
 *
 *
 */

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * settings
 * @since 		0.0.1
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 		0.0.1
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 * 
 */
class Payze_Simple_Payment_Form {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 		0.0.1
	 * @access 		protected
	 * @var 		Payze_Simple_Payment_Form_Loader 		$loader 		Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 		0.0.1
	 * @access 		protected
	 * @var 		string 			$plugin_name 		The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Sanitizer for cleaning user input
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      Payze_Simple_Payment_Form_Sanitize    $sanitizer    Sanitizes data
	 */
//****	private $sanitizer;

	/**
	 * The current version of the plugin.
	 *
	 * @since 		0.0.1
	 * @access 		protected
	 * @var 		string 			$version 		The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since 		0.0.1
	 */
	public function __construct() {

		$this->plugin_name = 'payze-simple-payment-form';
		$this->version = '0.0.1';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Payze_Simple_Payment_Form_Loader. Orchestrates the hooks of the plugin.
	 * - Payze_Simple_Payment_Form_i18n. Defines internationalization functionality.
	 * - Payze_Simple_Payment_Form_Admin. Defines all hooks for the dashboard.
	 * - Payze_Simple_Payment_Form_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payze-simple-payment-form-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-payze-simple-payment-form-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-payze-simple-payment-form-public.php';

		/**
		 * The class responsible for defining all actions creating the templates.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-payze-simple-payment-form-template-functions.php';

		/**
		 * The class responsible for processing Payze API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payze-v1-api.php';


		/**
		 * The class responsible for sanitizing user input
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payze-simple-payment-form-sanitize.php';

		$this->loader = new Payze_Simple_Payment_Form_Loader();
		$this->sanitizer = new Payze_Simple_Payment_Form_Sanitize();

	}


	/**
	 * Register all the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Payze_Simple_Payment_Form_Admin( $this->get_plugin_name(), $this->get_version() );

	//	$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
	//$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_pspf_payment' );
	//	$this->loader->add_action( 'init', $plugin_admin, 'new_taxonomy_type' );
		$this->loader->add_filter( 'plugin_action_links_' . PAYZE_SIMPLE_PAYMENT_FORM_FILE, $plugin_admin, 'link_settings' );
		$this->loader->add_action( 'plugin_row_meta', $plugin_admin, 'link_row', 10, 2 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
	//	$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );
	//	$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_notices_init' );

	} // define_admin_hooks()

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 */
	private function define_public_hooks() {

		$plugin_public = new Payze_Simple_Payment_Form_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', $this->get_version(), TRUE );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', $this->get_version(), TRUE );
		$this->loader->add_filter( 'single_template', $plugin_public, 'single_cpt_template' );

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		/**
		 * Action instead of template tag.
		 *
		 * do_action( 'payze_simple_payment_form' );
		 *
		 * @link 	http://nacin.com/2010/05/18/rethinking-template-tags-in-plugins/
		 */
		$this->loader->add_action( 'pspf_custom_payment_form', $plugin_public, 'list_openings' );



	} // define_public_hooks()

	/**
	 * Register all the hooks related to the templates.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_template_hooks() {

		$plugin_templates = new Payze_Simple_Payment_Form_Template_Functions( $this->get_plugin_name(), $this->get_version() );

		// Loop

		// Single
		$this->loader->add_action( 'payze-simple-payment-form-single-content', $plugin_templates, 'single_post_title' );
		$this->loader->add_action( 'payze-simple-payment-form-single-content', $plugin_templates, 'single_post_content', 15 );

	} // define_template_hooks()

	// define_shared_hooks()

	// define_metabox_hooks()

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 *
	 * @since 		0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 		0.0.1
	 * @return 		string 					The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 		0.0.1
	 * @return 		Payze_Simple_Payment_Form_Loader 		Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 		0.0.1
	 * @return 		string 					The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
