<?php

/**
 * Fired during plugin activation
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 		1.0.0
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 * @author 		Slushman <chris@slushman.com>
 */
class Payze_Simple_Payment_Form_Activator {

	/**
	 * Declare custom post types, taxonomies, and plugin settings
	 * Flushes rewrite rules afterwards
	 *
	 * @since 		1.0.0
	 * @noinspection PhpIncludeInspection
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-payze-simple-payment-form-admin.php';

		//No need for such a simple plugin without maintenance.

	} // activate()
} // class
