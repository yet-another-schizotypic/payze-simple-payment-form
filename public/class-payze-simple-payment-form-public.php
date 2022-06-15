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
 * The public-facing functionality of the plugin.
 *
 * @since        0.0.1
 * @link       http://slushman.com
 * @package    Payze_Simple_Payment_Form
 * @subpackage    Payze_Simple_Payment_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage    Payze_Simple_Payment_Form/public
 *
 */
class Payze_Simple_Payment_Form_Public {

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
	 * @plugin_name        string            $Payze_Simple_Payment_Form        The name of the plugin.
	 * @version        string            $version            The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->set_options();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since        0.0.1
	 */

	private function set_options() {
		$this->options = get_option( $this->plugin_name . '-options' );
	}


	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payze-simple-payment-form-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Processes shortcode pspf_custom_payment_form. Uses _GET or _POST queries info to choose actions to perform (
	 * process query to Payze or simple draw the payment form where the shortcode placed)
	 *
	 *
	 * @param array $atts The attributes from the shortcode
	 *
	 * @return    mixed    $output        Output of the buffer
	 */
	public function pspf_process_payze_payment_form_actions( $atts = array() ) {

		if ( isset( $_POST['submitted'] ) ) {
			$payze_api = new Payze_V1_API( $this->options );
			$payze_api->do_user_redirect_to_bank_payment_form( $_POST['nickName'], $_POST['amountToPay'] );

		}
		if ( isset( $_GET['payment_transaction_id'] ) ) {
			$payze_api = new Payze_V1_API( $this->options );
			$payze_api->process_form_after_bank_redirection( $_GET['payment_transaction_id'] );
		}

		return $this->pspf_display_payment_form( $atts );

	}

	/**
	 * Creates payment form HTML output
	 */
	public function pspf_display_payment_form( $atts = array() ) {
		$payment_form_template = plugin_dir_path( __DIR__ ) . 'includes/partials/' . $this->plugin_name . '-payment-form.php';

		return require( $payment_form_template );
	}

	/**
	 * Registers [pspf_custom_payment_form] shortcode
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'pspf_custom_payment_form', array( $this, 'pspf_process_payze_payment_form_actions' ) );

		return true;

	}
}
