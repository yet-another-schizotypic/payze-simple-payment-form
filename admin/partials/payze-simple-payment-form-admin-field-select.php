<?php /*
 * Copyright (c) 2022.
 *
 *
 */ /*
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

/**
 * Provides the markup for a select field
 *
 * @link       http://slushman.com
 * @since      0.0.1
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage Payze_Simple_Payment_Form/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'employees' ); ?>
    : </label><?php

}

?><select
        aria-label="<?php esc_attr( _e( $atts['aria'], 'payze-simple-payment-form' ) ); ?>"
        class="<?php echo esc_attr( $atts['class'] ); ?>"
        id="<?php echo esc_attr( $atts['id'] ); ?>"
        name="<?php echo esc_attr( $atts['name'] ); ?>"><?php

	if ( ! empty( $atts['blank'] ) ) {

		?>
        <option value><?php esc_html_e( $atts['blank'], 'payze-simple-payment-form' ); ?></option><?php

	}

	foreach ( $atts['selections'] as $selection ) {

		if ( is_array( $selection ) ) {

			$label = $selection['label'];
			$value = $selection['value'];

		} else {

			$label = strtolower( $selection );
			$value = strtolower( $selection );

		}

		?>
        <option
        value="<?php echo esc_attr( $value ); ?>" <?php
		selected( $atts['value'], $value ); ?>><?php

		esc_html_e( $label, 'payze-simple-payment-form' );

		?></option><?php

	} // foreach

	?></select>
<span class="description"><?php esc_html_e( $atts['description'], 'payze-simple-payment-form' ); ?></span>

