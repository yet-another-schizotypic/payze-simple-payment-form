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
 * This class encapsulates Payze API and provides functions to use two API calls: justPay and getTransactionInfo
 * @link  https://docs.payze.io/reference/transaction-information
 *
 */
class Payze_V1_API {
	private $api_key_to_use;
	private $api_secret_to_use;
	private $current_page_url;
	private $webhook_url;
	private $currency;
	private $locale_info;
	private $payze_api_url;
	private $default_transaction_status;


	function __construct( array $settings_context ) {
		$default_transaction_status = "UNKNOWN";
		if ( null !== $settings_context['pspf-operating-mode'] ) {
			if ( $settings_context['pspf-operating-mode'] === 'DEMO-GEL' ) {
				$this->api_key_to_use    = $settings_context['pspf-demo-key'];
				$this->api_secret_to_use = $settings_context['pspf-demo-secret'];
			} else {
				$this->api_key_to_use    = $settings_context['pspf-production-key'];
				$this->api_secret_to_use = $settings_context['pspf-production-secret'];
			}
			$this->currency    = substr( $settings_context['pspf-operating-mode'], - 3 );
			$this->locale_info = localeconv();
			global $wp;
			$this->current_page_url     = home_url( add_query_arg( array(), $wp->request ) );
			$this->webhook_url          = isset( $settings_context['pspf-webhook-url'] ) ? home_url( add_query_arg( array(), $wp->request ) ) : $settings_context['pspf-webhook-url'];
			$this->payze_api_url        = "https://payze.io/api/v1";

		} else {
			$this->show_message_redirect_and_die( 'FAILURE', 'Unknown or unset OPERATING_MODE', 'This a bug, please contact technical support' );
		}
	}

	/**
	 * This method shows given message and redirects the user back to the page where Payze Simple Payment form located
	 *
	 * @param $category
	 * Valid values are 'Success' and 'Failure' (case insensitive)
	 *
	 * @param $short_message
	 * Short description (first line of message)
	 *
	 * @param $details
	 * Details of message
	 *
	 * @return void
	 * @uses wp_die()
	 *
	 */

	private function show_message_redirect_and_die( $category, $short_message, $details ) {

		$message = '<style>
						img {
  							display: block;
  							margin-left: auto;
  							margin-right: auto;
						}
						h1 {
 							 text-align: center;
						}
					</style>';

		switch ( strtoupper( $category ) ) {
			case 'SUCCESS':
				$icon                = '/wp-content/plugins/payze-simple-payment-form/public/img/success-icon.png';
				$category_message    = "OK! Everything went fine.";
				$basic_description   = 'Operation: <b>' . $short_message . '</b>. <br/>';
				$details_description = 'Status: <b>' . $details . '</b> <br/>';
				break;
			case 'FAILURE':
				$icon                = '/wp-content/plugins/payze-simple-payment-form/public/img/error-failure-icon.png';
				$category_message    = "Something went wrong!";
				$basic_description   = 'Error: <b>' . $short_message . '</b>. <br/>';
				$details_description = 'Details: <b>' . $details . '</b> <br/>';
				break;
			default:
				wp_die( "FATAL ERROR: incorrect <b>show_message_redirect_and_die</b> call." );
				break;
		}

		$message .= "<img src='$icon' alt='Failure icon'/><br/><br/><br/>";
		$message .= "<H1>$category_message</H1><br/>";
		$message .= $basic_description;
		$message .= $details_description;

		$timeout = 5;
		$message .= 'You will be redirected to <a href="' . $this->current_page_url . '">' . $this->current_page_url . '</a>';
		$status  = 303; # this might be debatable
		header( 'Refresh: ' . $timeout . ';' . $this->current_page_url );
		wp_die( $message, $category, array( 'response' => $status ) );
	}

	/**
	 * Public function used to redirect the user to Payze's / BoG's page and enter all the needed payment datd.
	 *
	 * @param $payer_nickname
	 * @param $payment_amount
	 *
	 * @return void
	 * @uses process_justPay_query_to_payze
	 *
	 */
	public function do_user_redirect_to_bank_payment_form( $payer_nickname, $payment_amount ) {

		$payment_amount_sanitized = sanitize_text_field( $payment_amount );
		$payment_amount_sanitized = str_replace( $this->locale_info['thousands_sep'], '', $payment_amount_sanitized );
		$payment_amount_sanitized = str_replace( ".", $this->locale_info['decimal_point'], $payment_amount_sanitized );
		$payment_amount_sanitized = str_replace( ",", $this->locale_info['decimal_point'], $payment_amount_sanitized );
		$payment_amount_sanitized = floatval( $payment_amount_sanitized );
		$this->process_justPay_query_to_payze( sanitize_text_field( $payer_nickname ), $payment_amount_sanitized );
	}

	/**
	 * Payze's justPay method wrapper
	 *
	 * @link https://docs.payze.io/reference/just-pay
	 *
	 * This method creates 'psfp_payment' type post in WordPress (it' used as transaction record in payment log). It processes
	 * justPay query, gets unique transaction link / URL from Payze and redirects the user there.
	 *
	 * Later, when the user enter all the needed data on Payze's / BoG's site, he/she will be redirected back.
	 *
	 * @param $payer_nickname
	 * Nickname, email or other ID of a payer.
	 *
	 * @param $payment_amount
	 * How much the user is aiming to pay
	 *
	 * @return void
	 */
	private function process_justPay_query_to_payze( $payer_nickname, $payment_amount ) {
		$payze_responce     = $this->process_post_query_to_payze( 'justPay', array(
			'payer_nickname' => $payer_nickname,
			'payment_amount' => $payment_amount
		) );
		$payze_raw_response = $payze_responce['payze_raw_response'];

		if ( $payze_responce['httpCode'] !== 200 ) {
			$error_message_details = "HTTP Code: " . $payze_responce['httpCode'] . ". Error message from server: " . json_decode( $payze_raw_response )->message;
			$this->show_message_redirect_and_die( 'FAILURE', "An error occurred attempting sending request to Payze.io ", $error_message_details );
		}

		$transactionUrl = json_decode( $payze_raw_response )->response->transactionUrl;
		if ( isset( $transactionUrl ) ) {
			$post_title = "Payer's nickname: " . sanitize_text_field( $payer_nickname ) . '.  Amount: ' . sanitize_text_field( $payment_amount ) . ". " . $this->currency . ". Status: " . $this->default_transaction_status;
			$post       = array(
				'post_type'   => 'pspf_payment',
				'post_title'  => $post_title,
				'post_status' => 'publish',
				'post_author' => 1,
			);
			$post_id    = wp_insert_post( $post );
			add_post_meta( $post_id, 'payer_nickname', sanitize_text_field( $payer_nickname ), true );
			add_post_meta( $post_id, 'payment_amount', sanitize_text_field( $payment_amount ), true );
			add_post_meta( $post_id, 'payze_url', sanitize_text_field( $transactionUrl ), true );
			add_post_meta( $post_id, 'payze_transaction_id', sanitize_text_field( sanitize_text_field( json_decode( $payze_raw_response )->response->transactionId ) ), true );
		}

		if ( wp_redirect( $transactionUrl ) ) {
			( $transactionUrl );
			exit;
		} else {
			$this->show_message_redirect_and_die( 'FAILURE', "We couldn't receive correct transactionURL from Payze.", "Wrong URL: $transactionUrl" );
		}

	}

	/**
	 * Encapsulates POST queries to Payze API. Only two methods currently supported: justPay and getTransactionInfo
	 *
	 * @link https://docs.payze.io/reference/transaction-information
	 * @link https://docs.payze.io/reference/just-pay
	 *
	 * @uses curl
	 *
	 * @param $query_type
	 * String value: "justPay" or "getTransactionInfo"
	 *
	 * @param array $query_data
	 * Array must contain keys 'payment_amount' and 'payer_nickname' (for 'justPay') or 'transaction_id' (for 'getTransactionInfo')
	 *
	 * @return array
	 * Returned array contains the following keys: 'payze_raw_response', 'error', 'httpCode'
	 */
	private function process_post_query_to_payze( $query_type, array $query_data ) {

		$headers   = array(
			"Accept: application/json",
			"Content-Type: application/json; charset=utf-8"
		);
		$post_body = '';
		switch ( $query_type ) {
			case 'justPay':
				$post_body = array(
					'method' => 'justPay',
					'data'   => array(
						'amount'        => $query_data['payment_amount'],
						'currency'      => $this->currency,
						'callback'      => $this->current_page_url,
						'callbackError' => $this->current_page_url,
						'preauthorize'  => false,
						'lang'          => 'EN',
						'hookUrl'       => $this->webhook_url,
						'info'          => array(
							'payer_nickname' => $query_data['payer_nickname']
						)
					)
				);
				break;
			case 'getTransactionInfo':

				$post_body = array(
					"method" => "getTransactionInfo",
					"data"   => array( "transactionId" => $query_data['transaction_id'] )
				);

				break;
			default:
				$this->show_message_redirect_and_die( 'FAILURE', "Unknown Pazye API query: $query_type", "Please reload the page and try once again." );
		}
		$payze_api_credentials = [ "apiKey" => $this->api_key_to_use, "apiSecret" => $this->api_secret_to_use ];
		$post_body             = array_merge( $payze_api_credentials, $post_body );
		$post_string           = json_encode( $post_body );
		$request               = curl_init( $this->payze_api_url );

		curl_setopt( $request, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $request, CURLOPT_POSTFIELDS, $post_string );
		curl_setopt( $request, CURLOPT_URL, $this->payze_api_url );
		curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $request, CURLOPT_CONNECTTIMEOUT, 90 );
		curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $request, CURLOPT_HEADER, 0 );

		$post_response = curl_exec( $request );
		$error         = curl_error( $request );
		$httpCode      = curl_getinfo( $request, CURLINFO_HTTP_CODE );
		curl_close( $request ); // close curl object

		return array( 'payze_raw_response' => $post_response, 'error' => $error, 'httpCode' => $httpCode );

	}

	/**
	 * Called indirectly when the user was redirected back from Payze's / BoG's payment form. Looks for the relevant record
	 * ('psfp_payment' post with given 'transaction_id' value, processes Payze's info and updates the transaction record info,
	 * shows the user whether the payment transaction was successful or not.
	 *
	 * @link https://docs.payze.io/reference/transaction-information
	 *
	 * @param $transaction_id
	 *
	 * @return void
	 */
	public function process_form_after_bank_redirection( $transaction_id ) {

		if ( preg_match( "/^[A-Z\d]{32}$/", $transaction_id ) ) {

			$transaction_info   = $this->process_getTransactionInfo_query_to_payze( $transaction_id );
			$transaction_status = $transaction_info['payze_status'];
			$rejection_reason   = $transaction_info['payze_rejection_reason'];

			$post_args              = array(
				'posts_per_page' => - 1,
				'post_type'      => 'pspf_payment',
				'meta_key'       => 'payze_transaction_id',
				'meta_value'     => $transaction_id
			);
			$payment_record         = new WP_Query( $post_args );
			$payment_record_post_id = $payment_record->posts[0]->ID;

			$position = strpos( $payment_record->posts[0]->post_title, 'Status:' );
			if ( ! $position ) {
				$position = strlen( $payment_record->posts[0]->post_title ) - 1;
			}
			$payment_record_new_title = substr( $payment_record->posts[0]->post_title, 0, $position - strlen( $payment_record->posts[0]->post_title ) );

			if ( strcasecmp( $transaction_status, 'Committed' ) === 0 ) {
				$payment_record_new_title .= " Status: " . $transaction_status . " — Successfull payment";
				$status                   = 'SUCCESS';
			} else {
				$payment_record_new_title .= " Status: " . $transaction_status . " — " . $rejection_reason;
				$status                   = 'FAILURE';
			}

			$post_update = array(
				'ID'         => $payment_record_post_id,
				'post_title' => $payment_record_new_title
			);

			wp_update_post( $post_update );
			add_post_meta( $payment_record_post_id, 'payze_status', $transaction_status, true );
			add_post_meta( $payment_record_post_id, 'payze_rejection_reason', $rejection_reason, true );

			$this->show_message_redirect_and_die( $status, 'payment status is ' . $transaction_status, isset( $rejection_reason ) ? $rejection_reason : "Thank you for your payment!" );

		} else {
			$this->show_message_redirect_and_die( 'Failure', "Something went wrong. Please try again or contact technical support.", "Transaction ID is $transaction_id" );
		}

	}

	/**
	 * Small wrapper for process_form_after_bank_redirection
	 *
	 * @param $transaction_id
	 *
	 * @return array
	 */
	private function process_getTransactionInfo_query_to_payze( $transaction_id ) {
		$payze_responce     = $this->process_post_query_to_payze( 'getTransactionInfo', array( 'transaction_id' => $transaction_id ) );
		$payze_raw_response = $payze_responce['payze_raw_response'];
		$httpCode           = $payze_responce['httpCode'];
		if ( $httpCode !== 200 ) {
			$error_message = "An error occurred attempting to get info about transaction $transaction_id";
			$error_details = "HTTP Code: " . $payze_responce['httpCode'] . ". Error message from server: " . json_decode( $payze_raw_response )->message;
			$this->show_message_redirect_and_die( 'FAILURE', $error_message, $error_details );
		}
		$payze_post_response    = json_decode( $payze_raw_response );
		$payze_status           = sanitize_text_field( $payze_post_response->response->status );
		$payze_rejection_reason = isset( $payze_post_response->response->rejectionReason ) ? sanitize_text_field( $payze_post_response->response->rejectionReason ) : "";

		return array( 'payze_status' => $payze_status, 'payze_rejection_reason' => $payze_rejection_reason );
	}

}

