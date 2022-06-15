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
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @author                Vitalii Lobanov
 * @link                https://bootandpencil.com/lobanov
 * @since                0.0.1
 * @package            Payze_Simple_Payment_Form
 *
 * @wordpress-plugin
 * Plugin Name:        Payze Simple Payment Form
 * Plugin URI:            https://bootandpencil.com/blog/payze-simple-payment-form/
 * Description:        A simple way to manage job opening posts
 * Version:            0.0.1
 * Author:                Vitalii Lobanov
 * Author URI:            https://bootandpencil.com/lobanov
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:        payze-simple-payment-form
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function deactivate_Payze_Simple_Payment_Form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-payze-simple-payment-form-deactivator.php';
	Payze_Simple_Payment_Form_Deactivator::deactivate();
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-payze-simple-payment-form-activator.php
 */
function activate_Payze_Simple_Payment_Form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-payze-simple-payment-form-activator.php';
	Payze_Simple_Payment_Form_Activator::activate();
}


// Used for referring to the plugin file or basename
if ( ! defined( 'PAYZE_SIMPLE_PAYMENT_FORM_FILE' ) ) {
	define( 'PAYZE_SIMPLE_PAYMENT_FORM_FILE', plugin_basename( __FILE__ ) );
}

register_activation_hook( __FILE__, 'activate_Payze_Simple_Payment_Form' );
register_deactivation_hook( __FILE__, 'deactivate_Payze_Simple_Payment_Form' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-payze-simple-payment-form.php';

/**
 * @return void
 * @author
 *
 */
function run_Payze_Simple_Payment_Form() {

	$plugin = new Payze_Simple_Payment_Form();
	$plugin->run();

}

run_Payze_Simple_Payment_Form();
