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
 * settings
 * @since        0.0.1
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage    Payze_Simple_Payment_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage    Payze_Simple_Payment_Form/public
 *
 */
class Payze_Simple_Payment_Form_Public {

	/**
	 * The plugin options.
	 *
	 * @since        0.0.1
	 * @access        private
	 * @var        string $options The plugin options.
	 */
//*****	private $options;

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

	//TODO: не удалять
/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	}


	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payze-simple-payment-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Processes shortcode pspf_custom_payment_form
	 *
	 * @param array $atts The attributes from the shortcode
	 *
	 * @return    mixed    $output        Output of the buffer
	 * @uses    get_layout
	 *
	 * @uses    get_option
	 */
	public function pspf_process_payze_payment_form_actions( $atts = array() ) {

		if ( isset( $_POST['submitted'] ) ) {
			$payze_api = new Payze_V1_API( $this->options );
			$res       = $payze_api->do_user_redirect_to_bank_payment_form( $_POST['nickName'], $_POST['amountToPay'] );
			//$this->pspf_process_payment();
		}
		if ( isset( $_GET['payment_transaction_id'] ) ) {
			$payze_api = new Payze_V1_API( $this->options );
			$res       = $payze_api->process_form_after_bank_redirection( $_GET['payment_transaction_id'] );
		}

		return $this->pspf_display_payment_form( $atts );

	} // list_openings()

		/**
	 * Creates payment form HTML output
	 *
	 * @param $atts
	 *
	 * @return string
	 */

	public function pspf_display_payment_form( $atts = array() ) {
		ob_start();

		$tmp = plugin_dir_path( __DIR__ ) . 'includes/partials/' . $this->plugin_name . '-payment-form.php';
		echo $tmp;

		$out = require( $tmp );


		extract( shortcode_atts( array(
			'el_class' => '',
			'el_id'    => '',
		), $atts ) );

		ob_end_clean();

		return $out;
	} // register_shortcodes()

	/**
	 * Adds a default single view template for a job opening
	 *
	 * @param string $template The name of the template
	 *
	 * @return    mixed                        The single template
	 */
	/*	public function single_cpt_template( $template ) {

			global $post;

			$return = $template;

			if ( $post->post_type == 'pspf_payment' ) {

				$return = payze_simple_payment_form_get_template( 'single-job' );

			}

			return $return;

		} // single_cpt_template() */

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'pspf_custom_payment_form', array( $this, 'pspf_process_payze_payment_form_actions' ) );

		return true;

	} // set_options()


} // class
