<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @author 				Vitalii Lobanov
 * @link 				http://slushman.com
 * @since 				0.0.1
 * @package 			Payze_Simple_Payment_Form
 *
 * @wordpress-plugin
 * Plugin Name: 		Payze Simple Payment Form
 * Plugin URI: 			https://bootandpencil.com/blog/payze-simple-payment-form/
 * Description: 		A simple way to manage job opening posts
 * Version: 			0.0.1
 * Author: 				Vitalii Lobanov
 * Author URI: 			https://bootandpencil.com/lobanov
 * License: 			GPL-2.0+
 * License URI: 		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 		payze-simple-payment-form
 * Domain Path: 		/languages
 */


//TODO: Перед релизом настроить адекватную переадресацию, чтобы реально вела на страничку с плагином
//TODO: https://bootandpencil.com/lobanov — проверить, чтобы работало
//TODO: адекватное объявление о том, на базе чего / авторов всё это сделано

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 		0.0.1
 */
function run_Payze_Simple_Payment_Form() {

	$plugin = new Payze_Simple_Payment_Form();
	$plugin->run();

}
run_Payze_Simple_Payment_Form();
