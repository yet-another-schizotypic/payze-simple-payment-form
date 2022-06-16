<?php /*
 * Copyright (c) 2022.
 * This code was made by copy-paste and some monkey typing.
 *
 * The most reasonable parts are from the «Now Hiring» plugin by slushman
 *  (https://github.com/slushman/now-hiring), the «WordPress Boilerplate» by
 *  DevinVinson (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
 *  and « Authorize.net - Simple Donations» by Aman Verma (https://twitter.com/amanverma217).
 *
 * License: GPLv2 or later.
 *
 *
 */

/*
 * Copyright (c) 2022.
 * This code was made by copy-paste and some monkey typing.
 *
 * The most significant parts are taken from the «Now Hiring» plugin by slushman aka
 * Chris Wilcoxson (https://github.com/slushman/now-hiring), the «WordPress Boilerplate» by
 *  DevinVinson (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
 *  and the «Authorize.net - Simple Donations» by Aman Verma (https://twitter.com/amanverma217).
 *
 * License: GPLv2 or later.
 *
 *
 */
/**
 * Provides the markup for any text field
 *
 * @link       http://slushman.com
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage Payze_Simple_Payment_Form/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><label
    for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'payze-simple-payment-form' ); ?>
    : </label><?php

}

?><input
    class="<?php echo esc_attr( $atts['class'] ); ?>"
    id="<?php echo esc_attr( $atts['id'] ); ?>"
    name="<?php echo esc_attr( $atts['name'] ); ?>"
    placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
    type="<?php echo esc_attr( $atts['type'] ); ?>"
    value="<?php echo esc_attr( $atts['value'] ); ?>" /><?php

if ( ! empty( $atts['description'] ) ) {

	?><span class="description"><?php esc_html_e( $atts['description'], 'payze-simple-payment-form' ); ?></span><?php

}