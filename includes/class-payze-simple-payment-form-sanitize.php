<?php

/**
 * Sanitize anything
 *
 * @since      0.0.1
 *
 * @package    Payze Simple Payment Form
 * @subpackage Payze Simple Payment Form/includes
 */

class Payze_Simple_Payment_Form_Sanitize {

	/**
	 * The data to be sanitized
	 *
	 * @access 	private
	 * @since 	0.1
	 * @var 	string
	 */
	private $data = '';

	/**
	 * The type of data
	 *
	 * @access 	private
	 * @since 	0.1
	 * @var 	string
	 */
	private $type = '';

	/**
	 * Constructor
	 */
	public function __construct() {

		// Nothing to see here...

	} // __construct()

	/**
	 * Cleans the data
	 *
	 * @access 	public
	 * @since 	0.1
	 *
	 * @return  mixed         The sanitized data
	 */
	public function clean() {

		$sanitized = '';

		/**
		 * Add additional santization before the default sanitization
		 */
		do_action( 'pspf_pre_sanitize', $sanitized );

		switch ( $this->type ) {

			case 'color'			:
			case 'radio'			:
			case 'select'			: $sanitized = $this->sanitize_random( $this->data ); break;

			case 'date'				:
			case 'datetime'			:
			case 'datetime-local'	:
			case 'time'				:
			case 'week'				: $sanitized = strtotime( $this->data ); break;

			case 'number'			:
			case 'range'			: $sanitized = intval( $this->data ); break;

			case 'hidden'			:
			case 'month'			:
			case 'text'				: $sanitized = sanitize_text_field( $this->data ); break;

			case 'checkbox'			: $sanitized = ( isset( $this->data ) ? 1 : 0 ); break;
			case 'editor' 			: $sanitized = wp_kses_post( $this->data ); break;
			case 'email'			: $sanitized = sanitize_email( $this->data ); break;
			case 'file'				: $sanitized = sanitize_file_name( $this->data ); break;

			case 'textarea'			: $sanitized = esc_textarea( $this->data ); break;
			case 'url'				: $sanitized = esc_url( $this->data ); break;

		} // switch

		/**
		 * Add additional santization after the default .
		 */
		do_action( 'pspf_post_sanitize', $sanitized );

		return $sanitized;

	} // clean()

	// validate_date()



	/**
	 * Performs general cleaning functions on data
	 *
	 * @param 	mixed 	$input 		Data to be cleaned
	 * @return 	mixed 	$return 	The cleaned data
	 */
	private function sanitize_random( $input ) {

			$one	= trim( $input );
			$two	= stripslashes( $one );

		return htmlspecialchars( $two );

	} // sanitize_random()

	/**
	 * Sets the data class variable
	 *
	 * @param 	mixed 		$data			The data to sanitize
	 */
	public function set_data( $data ) {

		$this->data = $data;

	} // set_data()

	/**
	 * Sets the type class variable
	 *
	 * @param 	string 		$type			The field type for this data
	 */
	public function set_type( $type ) {

		$check = '';

		if ( empty( $type ) ) {

			$check = new WP_Error( 'forgot_type', __( 'Specify the data type to sanitize.', 'payze-simple-payment-form' ) );

		}

		if ( is_wp_error( $check ) ) {

			wp_die( $check->get_error_message(), __( 'Forgot data type', 'payze-simple-payment-form' ) );

		}

		$this->type = $type;

	} // set_type()

} // class