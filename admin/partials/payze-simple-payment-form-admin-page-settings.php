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
 * Provides admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://slushman.com
 * @since      0.0.1
 *
 * @package    Payze Simple Payment Form
 * @subpackage Payze Simple Payment Form/admin/partials
 */

?><h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
<form method="post" action="options.php"><?php

	settings_fields( $this->plugin_name . '-options' );

	do_settings_sections( $this->plugin_name );

	submit_button( 'Save Settings' );

	?></form>