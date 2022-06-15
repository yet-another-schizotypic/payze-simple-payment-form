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
 * Provide admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link https://bootandpencil.com/lobanov
 * @since      0.0.1
 *
 * @package    Payze Simple Payment Form
 * @subpackage Payze Simple Payment Form/admin/partials
 */

?><h1>Payze Simple Payment Form Help</h1>

<h2>Shortcode</h2>

<p>You can use this plugin by placement the following shortcode:</p>

<pre>
<code>[pspf_custom_payment_form]</code></pre>

<h2><br/>
    How to add a shortcode?</h2>

<ol>
    <li>Go to the post or page where you want to place the shortcode;</li>
    <li>Click &quot;Add block&quot;, and choose &quot;Shortcode&quot;:<br/>
    <li>Add Payze Simple Payment Form shortcode (see above), the&nbsp;square brackets is a must:&nbsp;<br/>
    <li>Save and reload the page;</li>
</ol>

<h2>Where can I get Payze Credentials?</h2>

<ol>
    <li>Go to <a href="https://payze.io/">Payze.io</a>&nbsp;and sign up;</li>
    <li>Fill up the KYC / questionnaire;&nbsp;</li>
    <li>Wait for the approval;</li>
    <li>Go to Dashboard, and navigate to &quot;API keys&quot; section:<br/>

</ol>

<h2>How can I change the appearance of the payment form?</h2>

<p>Currently, the plugin doesn&#39;t have a WYSIWYG editor or UI to tune up the form appearance. If you want to change
    the form&#39;s look and feel, you can edit <strong>./payze-simple-payment-form/public/css/payze-simple-payment-form-public.css</strong>
    file. You should be familiar with CSS markup language and have basic WordPress administration skills.&nbsp;</p>
<p>Advanced users also can change the HTML template directly by editing <b>/includes/partials/payze-simple-payment-form-payment-form.php</b>
    file.</p>