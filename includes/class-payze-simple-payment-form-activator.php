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

/*		//!!**Payze_Simple_Payment_Form_Admin::new_cpt_job();
		//!!**Payze_Simple_Payment_Form_Admin::new_taxonomy_type();

		flush_rewrite_rules();

		$opts 		= array();
		$options 	= Payze_Simple_Payment_Form_Admin::get_options_list();

		foreach ( $options as $option ) {

			$opts[ $option[0] ] = $option[2];

		}

		update_option( 'payze-simple-payment-form-options', $opts );

		Payze_Simple_Payment_Form_Admin::add_admin_notices();*/

	} // activate()
} // class
