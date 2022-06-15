<?php

/**
 * The public-facing functionality of the plugin.
 *
 * settings
 * @since 		0.0.1
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/public
 * 
 */
class Payze_Simple_Payment_Form_Public {

	/**
	 * The plugin options.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
//*****	private $options;

	/**
	 * The ID of this plugin.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		0.0.1
	 * @param 		string 			$Payze_Simple_Payment_Form 		The name of the plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_options();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 		0.0.1
	 */

	//TODO: не удалять
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payze-simple-payment-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 		0.0.1
	 */
	//TODO: не удалять
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/payze-simple-payment-form-public.js', array( 'jquery' ), $this->version, true );

	}

    /**
     * Creates payment form HTML output
     *
     * @param $atts
     * @return string
     */

    public function psfp_display_payment_form ($atts = array()){
        ob_start();

        $tmp = plugin_dir_path(  __DIR__  ) . 'includes/partials/' . $this->plugin_name . '-payment-form.php' ;
        echo $tmp;

        $out = require( $tmp);



        extract(shortcode_atts(array(
            'el_class' => '',
            'el_id' => '',
        ),$atts));

        ob_end_clean();
        return $out;
    }

    public function psfp_process_payment (){
        if ( isset( $_POST['submitted'] ) ) {
            wp_die('Submitted! 45664565465464654654654');
        }

    }

	/**
	 * Processes shortcode pspf_custom_payment_form
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function psfp_process_payze_payment_form_actions($atts = array() ) {

		//TODO: написать логику, опции отсюда доступны: $this->options

		if ( isset( $_POST['submitted'] )) {
			$payze_api = new Payze_V1_API($this->options);
			$res = $payze_api->do_user_redirect_to_bank_payment_form($_POST['nickName'], $_POST['amountToPay']);
			//$this->psfp_process_payment();
		}
		if (isset($_GET['payment_transaction_id'])) {
			$payze_api = new Payze_V1_API($this->options);
			$res = $payze_api->process_form_after_bank_redirection($_GET['payment_transaction_id']);
		}
        return $this->psfp_display_payment_form($atts);

	} // list_openings()

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {

		add_shortcode( 'pspf_custom_payment_form', array( $this, 'psfp_process_payze_payment_form_actions') );
		return true;

	} // register_shortcodes()

	/**
	 * Adds a default single view template for a job opening
	 *
	 * @param 	string 		$template 		The name of the template
	 * @return 	mixed 						The single template
	 */
/*	public function single_cpt_template( $template ) {

		global $post;

		$return = $template;

	    if ( $post->post_type == 'payment' ) {

			$return = payze_simple_payment_form_get_template( 'single-job' );

		}

		return $return;

	} // single_cpt_template() */

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	} // set_options()



} // class
