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
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage    Payze_Simple_Payment_Form/admin
 *
 */
class Payze_Simple_Payment_Form_Admin {

	/**
	 * The plugin options.
	 *
	 * @since        0.0.1
	 * @access        private
	 * @var        string $options The plugin options.
	 */
	private $options;

	/**
	 * The ID of this plugin.
	 *
	 * @since        0.0.1
	 * @access        private
	 * @var        string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since        0.0.1
	 * @access        private
	 * @var        string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since        0.0.1
	 * @plugin_name        string            $Payze_Simple_Payment_Form        The name of this plugin.
	 * @version        string            $version            The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->set_options();

	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	}

	/**
	 * Adds links to the plugin links row
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of row links
	 * @param 		string 		$file 		The name of the file
	 * @return 		array 					The modified array of row links
	 */
	public function link_row( $links, $file ) {

		if ( NOW_HIRING_FILE === $file ) {

			$links[] = '<a href="http://bootandpencil.com/lobanov">Twitter</a>';

		}

		return $links;

	} // link_row()



	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_settings( $links ) {

		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'edit.php?post_type=pspf_payment&page=' . $this->plugin_name . '-settings' ) ), esc_html__( 'Settings', 'payze-simple-payment-form' ) );

		return $links;

	}


	/**
	 * Creates a new custom post type
	 * @link       http://slushman.com
	 * @since    0.0.1
	 * @access    public
	 * @uses    register_post_type()
	 */
	public static function new_pspf_payment() {

		$cap_type = 'post';
		$plural   = 'Payments';
		$single   = 'Payment';
		$cpt_name = 'pspf_payment';

		$opts['can_export']           = true;
		$opts['capability_type']      = $cap_type;
		$opts['description']          = 'Payze payment record';
		$opts['exclude_from_search']  = true;
		$opts['has_archive']          = false;
		$opts['hierarchical']         = false;
		$opts['map_meta_cap']         = true;
		$opts['menu_icon']            = 'dashicons-money';
		$opts['menu_position']        = 25;
		$opts['public']               = false;
		$opts['publicly_querable']    = false;
		$opts['query_var']            = true;
		$opts['register_meta_box_cb'] = '';
		$opts['rewrite']              = false;
		$opts['show_in_admin_bar']    = true;
		$opts['show_in_menu']         = true;
		$opts['show_in_nav_menu']     = true;
		$opts['show_ui']              = true;
		$opts['supports']             = array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies']           = array();


		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_$cap_type";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_$cap_type";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_$cap_type";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";
		$opts['capabilities']['create_posts']           = false;


		$opts['labels']['edit_item']          = esc_html__( "Edit $single", 'payze-simple-payment-form' );
		$opts['labels']['name']               = esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['not_found']          = esc_html__( "No $plural Found", 'payze-simple-payment-form' );
		$opts['labels']['not_found_in_trash'] = esc_html__( "No $plural Found in Trash", 'payze-simple-payment-form' );
		$opts['labels']['parent_item_colon']  = esc_html__( "Parent $plural :", 'payze-simple-payment-form' );
		$opts['labels']['search_items']       = esc_html__( "Search $plural", 'payze-simple-payment-form' );
		$opts['labels']['singular_name']      = esc_html__( $single, 'payze-simple-payment-form' );
		$opts['labels']['view_item']          = esc_html__( "View $single", 'payze-simple-payment-form' );

		$opts['rewrite']['ep_mask']    = EP_PERMALINK;
		$opts['rewrite']['feeds']      = false;
		$opts['rewrite']['pages']      = true;
		$opts['rewrite']['slug']       = esc_html__( strtolower( $plural ), 'payze-simple-payment-form' );
		$opts['rewrite']['with_front'] = false;


		$opts = apply_filters( 'payze-simple-payment-form-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	}

	/**
	 * Adds a settings and help pages link to a menu
	 *
	 * @link        https://codex.wordpress.org/Administration_Menus
	 * @since        0.0.1
	 * @return        void
	 */
	public function add_menu() {

		add_submenu_page( 'edit.php?post_type=pspf_payment', apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Payze Simple Payment Form', 'payze-simple-payment-form' ) ), apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'payze-simple-payment-form' ) ), 'manage_options', $this->plugin_name . '-settings', array(
			$this,
			'page_options'
		) );

		add_submenu_page( 'edit.php?post_type=pspf_payment', apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Payze Simple Payment Form Help', 'payze-simple-payment-form' ) ), apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'payze-simple-payment-form' ) ), 'manage_options', $this->plugin_name . '-help', array(
			$this,
			'page_help'
		) );

	}

	/**
	 * Creates a select field
	 * @link       http://slushman.com
	 * Note: label is blank since it's created in the Settings API
	 *
	 */
	public function field_select( $args ) {

		$defaults['aria']        = '';
		$defaults['blank']       = '';
		$defaults['class']       = 'widefat';
		$defaults['context']     = '';
		$defaults['description'] = '';
		$defaults['label']       = '';
		$defaults['name']        = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections']  = array();
		$defaults['value']       = '';

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[ $atts['id'] ] ) ) {

			$atts['value'] = $this->options[ $atts['id'] ];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

		return true;
	}

	/**
	 * Creates a text field
	 * @link       http://slushman.com
	 *
	 * @param array $args The arguments for the field
	 *
	 */
	public function field_text( $args ) {

		$defaults['class']       = 'text widefat';
		$defaults['description'] = '';
		$defaults['label']       = '';
		$defaults['name']        = $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] = '';
		$defaults['type']        = 'text';
		$defaults['value']       = '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[ $atts['id'] ] ) ) {

			$atts['value'] = $this->options[ $atts['id'] ];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

		return true;
	}

	/**
	 * Creates the help page
	 *
	 * @return        void
	 * @since        0.0.1
	 */
	public function page_help() {

		include( plugin_dir_path( __FILE__ ) . 'partials/payze-simple-payment-form-admin-page-help.php' );

	}

	/**
	 * Creates the options page
	 *
	 * @return        void
	 * @since        0.0.1
	 */
	public function page_options() {

		include( plugin_dir_path( __FILE__ ) . 'partials/payze-simple-payment-form-admin-page-settings.php' );

	}

	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		add_settings_field( 'pspf-demo-key', apply_filters( $this->plugin_name . 'label-pspf-demo-key', esc_html__( 'DEMO key', 'payze-simple-payment-form' ) ), array(
			$this,
			'field_text'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "You can find DEMO key at Payze's dashboard.",
			'id'          => 'pspf-demo-key',
			'value'       => '',
		) );

		add_settings_field( 'pspf-demo-secret', apply_filters( $this->plugin_name . 'label-pspf-demo-secret', esc_html__( 'DEMO secret', 'payze-simple-payment-form' ) ), array(
			$this,
			'field_text'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "You can find DEMO secret at Payze's dashboard.",
			'id'          => 'pspf-demo-secret',
			'value'       => '',
		) );

		add_settings_field( 'pspf-production-key', apply_filters( $this->plugin_name . 'label-pspf-production-key', esc_html__( 'PRODUCTION key', 'payze-simple-payment-form' ) ), array(
			$this,
			'field_text'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "You can find PRODUCTION key at Payze's dashboard.",
			'id'          => 'pspf-production-key',
			'value'       => '',
		) );

		add_settings_field( 'pspf-production-secret', apply_filters( $this->plugin_name . 'label-pspf-production-secret', esc_html__( 'PRODUCTION secret', 'payze-simple-payment-form' ) ), array(
			$this,
			'field_text'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "You can find PRODUCTION secret at Payze's dashboard.",
			'id'          => 'pspf-production-secret',
			'value'       => '',
		) );

		add_settings_field( 'pspf-webhook-url', apply_filters( $this->plugin_name . 'label-pspf-webhook-url', esc_html__( "Webhook URL. You can use it for debugging. Leave blank if you don't know what is it.", 'payze-simple-payment-form' ) ), array(
			$this,
			'field_text'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "You can find DEMO secret at Payze's dashboard.",
			'id'          => 'pspf-webhook-url',
			'value'       => '',
		) );

		add_settings_field( 'pspf-operating-mode', apply_filters( $this->plugin_name . 'label-pspf-operating-mode', esc_html__( 'Operating mode', 'payze-simple-payment-form' ) ), array(
			$this,
			'field_select'
		), $this->plugin_name, $this->plugin_name . '-settings', array(
			'description' => "In DEMO / sandbox mode transactions actually do not happen, money won't be charged.",
			'id'          => 'pspf-operating-mode',
			'selections'  => array(
				array( 'label' => 'DEMO (sandbox) mode, Georgian Lari', 'value' => 'DEMO-GEL' ),
				array( 'label' => 'PRODUCTION (live) mode, United States Dollar', 'value' => 'PRODUCTION-USD' ),
				array( 'label' => 'PRODUCTION (live) mode, Euro', 'value' => 'PRODUCTION-EUR' ),
				array( 'label' => 'PRODUCTION (live) mode, Georgian Lari', 'value' => 'PRODUCTION-GEL' ),
			)
		) );


	}

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		add_settings_section( $this->plugin_name . '-settings', apply_filters( $this->plugin_name . 'section-title-settings', esc_html__( 'Settings', 'payze-simple-payment-form' ) ), array(), $this->plugin_name );

	}

	/**
	 * Registers plugin settings
	 *
	 * @return        void
	 * @since        0.0.1
	 */
	public function register_settings() {


		register_setting( $this->plugin_name . '-options', $this->plugin_name . '-options', array(
			$this,

		) );

	}

	/**
	 * Validates saved options
	 *
	 * @param array $input array of submitted plugin options
	 *
	 * @return        array                        array of validated plugin options
	 * @since        0.0.1
	 */
	public function validate_options( $input ) {

		$valid   = array();
		$options = $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			$valid[ $option[0] ] = $this->sanitizer( $type, $input[ $name ] );

		}

		return $valid;
	}

	/**
	 * Returns an array of options names, fields types, and default values
	 * @link       http://slushman.com
	 * @return        array            An array of options
	 */
	public static function get_options_list() {

		$options   = array();
		$options[] = array( 'pspf-demo-key', 'text' );
		$options[] = array( 'pspf-demo-secret', 'text' );
		$options[] = array( 'pspf-production-key', 'text' );
		$options[] = array( 'pspf-production-secret', 'text' );
		$options[] = array( 'pspf-webhook-url', 'text' );
		$options[] = array( 'pspf-operating-mode', 'select' );

		return $options;

	}

	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) {
			return false;
		}
		if ( empty( $data ) ) {
			return false;
		}

		$sanitizer = new Payze_Simple_Payment_Form_Sanitize();
		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );
		$return = $sanitizer->clean();
		unset( $sanitizer );

		return $return;
	}

}