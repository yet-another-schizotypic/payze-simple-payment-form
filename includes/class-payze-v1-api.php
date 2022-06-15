<?php

class Payze_V1_API
{

	private $api_key_to_use;
	private $api_secret_to_use;
	private $current_page_url;
	private $webhook_url;
	private $currency;
	private $locale_info;
	private $payze_api_url;


	function __construct(array $settings_context) {
		if (null !== $settings_context['psfp-operating-mode']){
			if ($settings_context['psfp-operating-mode'] === 'DEMO-GEL'){
				$this->api_key_to_use    = $settings_context['psfp-demo-key'];
				$this->api_secret_to_use = $settings_context['psfp-demo-secret'];
				} else {
				$this->api_key_to_use    = $settings_context['psfp-production-key'];
				$this->api_secret_to_use = $settings_context['psfp-production-secret'];
			}
			$this->currency              = substr( $settings_context['psfp-operating-mode'], - 3 );
			$this->locale_info           = localeconv();
			global $wp;
			$this->current_page_url      = home_url( add_query_arg( array(), $wp->request ) );
			$this->webhook_url           = isset( $settings_context['psfp-webhook-url']) ? home_url( add_query_arg( array(), $wp->request ) ) : $settings_context['psfp-webhook-url'];
			$this->payze_api_url               = "https://payze.io/api/v1";
		} else {
			wp_die ('Something went wrong: operating mode is not set');
		}
	}

	private function show_message_redirect_and_die ($category, $short_message, $details){


		$message = '<style>
						img {
  							display: block;
  							margin-left: auto;
  							margin-right: auto;
						}
					</style>';

		switch (strtoupper($category)){
			case 'SUCCESS':
				$icon = '/wp-content/plugins/payze-simple-payment-form/public/img/success-icon.png';

				break;
			case 'FAILURE':
				$message .= '<img src="/wp-content/plugins/payze-simple-payment-form/public/img/error-failure-icon.png" alt="Failure icon"/><br/><br/><br/>';
				$message .= '<H1>Something went wrong!</H1><br/>';
				$message .= 'Error: <b>' . $short_message . '</b>. <br/>';
				$message .= 'Details: <b>' . $details . '</b> <br/>';
				break;
			case 'INFO':
				$icon = '/wp-content/plugins/payze-simple-payment-form/public/img/info-icon.png';
				break;
			default:
				break;
		}

		$timeout  = 5;
		$message  .= 'You will be redirected to <a href="' . $this->current_page_url . '">' . $this->current_page_url . '</a>';
		$status   = 303; # this might be debatable
		header( 'Refresh: ' . $timeout . ';' . $this->current_page_url );
		wp_die( $message, $category, array( 'response' => $status ) );
	}

	private function process_post_query_to_payze($query_type, array $query_data){

		$headers = array(
			"Accept: application/json",
			"Content-Type: application/json; charset=utf-8"
		);
		$post_body = '';
		switch ($query_type) {
			case 'justPay':
				$post_body = array(
					'method'    => 'justPay',
					'data'      => array(
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
					"data" => array ("transactionId" => $query_data['transaction_id'])
				);

				break;
			default:
				wp_die("Unknown Pazye API query: $query_type, don't know how to process it. Please reload the page and try once again.");

		}
		$payze_api_credentials = ["apiKey" => $this->api_key_to_use, "apiSecret" => $this->api_secret_to_use];
		$post_body = array_merge($payze_api_credentials, $post_body);
		$post_string = json_encode( $post_body );
		$request     = curl_init( $this->payze_api_url );

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
		return array ('payze_raw_response' => $post_response, 'error' => $error, 'httpCode' => $httpCode);

	}

	private function process_justPay_query_to_payze($payer_nickname, $payment_amount){
		$payze_responce = $this->process_post_query_to_payze('justPay', array('payer_nickname' => $payer_nickname, 'payment_amount' => $payment_amount));
		$payze_raw_response = $payze_responce['payze_raw_response'];

		if ( $payze_responce['httpCode'] !== 200 ) {
			wp_die( "An error occurred attempting sending request to Payze.io " . "<br> HTTP Code: " . $payze_responce['httpCode']  . "<br> Error message from server: " . json_decode($payze_raw_response)->message);
		}

		$transactionUrl = json_decode( $payze_raw_response )->response->transactionUrl;
		if ( isset( $transactionUrl ) ) {

			//TODO: куда-то сюда проверку прошла оплата или нет, наверное, вебхуком
			//TODO: дефолтный заголовок до того, как банк редиректнет, что-то вроде: Unчего-то-там
			$post = array(
				'post_type'    => 'payment',
				'post_title'   => 'Payment - '. sanitize_text_field($payer_nickname). ' — '. sanitize_text_field($payment_amount) . " " . $this->currency,
				'post_status'  => 'publish',
				'post_author'  => 1,
			);
			$post_id = wp_insert_post( $post );
			add_post_meta($post_id, 'payer_nickname', sanitize_text_field($payer_nickname), true);
			add_post_meta($post_id, 'payment_amount', sanitize_text_field($payment_amount), true);
			add_post_meta($post_id, 'payze_url', sanitize_text_field($transactionUrl), true);
			add_post_meta($post_id, 'payze_transaction_id', sanitize_text_field(sanitize_text_field(json_decode( $payze_raw_response )->response->transactionId)), true);
		}

		if ( wp_redirect( $transactionUrl ) ) {
			( $transactionUrl );
			exit;
		}

		else {
			wp_die("We couldn't receive correct transactionURL from Payze. Check plugin settings and try once again.");
		}

	}

	public function do_user_redirect_to_bank_payment_form($payer_nickname, $payment_amount){

		$payment_amount_sanitized = sanitize_text_field($payment_amount);
		$payment_amount_sanitized= str_replace( $this->locale_info['thousands_sep'], '', $payment_amount_sanitized );
		$payment_amount_sanitized = str_replace( ".", $this->locale_info['decimal_point'], $payment_amount_sanitized );
		$payment_amount_sanitized = str_replace( ",", $this->locale_info['decimal_point'], $payment_amount_sanitized );
		$payment_amount_sanitized = floatval( $payment_amount_sanitized );
		$this->process_justPay_query_to_payze(sanitize_text_field($payer_nickname), $payment_amount_sanitized);
	}

	private function process_getTransactionInfo_query_to_payze($transaction_id){
		//TODO: счётчик количества рефрешей / повторов в заголовке поста
		//TODO: и картинку с символикой успеха / неуспеха транзакции
		$payze_responce = $this->process_post_query_to_payze('getTransactionInfo', array('transaction_id' => $transaction_id));
		$payze_raw_response = $payze_responce['payze_raw_response'];
		$httpCode = $payze_responce['httpCode'];
		if (  $httpCode !== 200 ) {
			wp_die( "An error occurred attempting to get info about transaction $transaction_id" . "<br> HTTP Code: " . $payze_responce['httpCode']  . "<br> Error message from server: " . json_decode($payze_raw_response)->message);
		}
		$payze_post_response = json_decode($payze_raw_response);
		$payze_status =	sanitize_text_field($payze_post_response->response->status);
		$payze_rejection_reason = sanitize_text_field($payze_post_response->response->rejectionReason);
		return array('payze_status' => $payze_status, 'payze_rejection_reason' => $payze_rejection_reason);
	}

	public function process_form_after_bank_redirection($transaction_id){

		if ( preg_match( "/^[A-Z\d]{32}$/", $transaction_id ) ) {

			$transaction_info = $this->process_getTransactionInfo_query_to_payze($transaction_id);
			$transaction_status = $transaction_info['payze_status'];
			$rejection_reason = $transaction_info['payze_rejection_reason'];

			if (strcasecmp($transaction_status, 'Committed') === 0){

				//TODO: инфу о том, что всё хорошо, записываем в пост payment
				wp_die('Thank you for your payment!');

			} else {
				$post_args = array(
					'posts_per_page'   => -1,
					'post_type'        => 'payment',
					'meta_key'         => 'payze_transaction_id',
					'meta_value'       => $transaction_id
				);
				$payment_record = new WP_Query( $post_args );
				$payment_record_post_id = $payment_record->posts[0]->ID;
				$payment_record_new_title = $payment_record->posts[0]->post_title . ": " . $transaction_status . " — " . $rejection_reason;
				$post_update = array(
					'ID'         => $payment_record_post_id,
					'post_title' => $payment_record_new_title
				);

				wp_update_post( $post_update );

				add_post_meta($payment_record_post_id, 'payze_status', $transaction_status, true);
				add_post_meta($payment_record_post_id, 'payze_rejection_reason', $rejection_reason, true);

				$location = $this->current_page_url;
				$this->show_message_redirect_and_die('Failure', 'payment status is ' . $transaction_status, $rejection_reason);

			}

		} else {
			wp_die ("Unknown transaction pattern: $transaction_id. Please try again or contact technical support.");
		}

	}

}