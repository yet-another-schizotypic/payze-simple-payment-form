<?php

/**
 * Provides the markup for payment form
 *
 * 
 * @since      0.0.1
 *
 * @package    Payze_Simple_Payment_Form
 * @subpackage Payze_Simple_Payment_Form/includes/partials
 */

$payment_form_html = '<form id="pspf-custom-payment-form" class="pspf-custom-payment-form" action="" method="post">
    <p><input type="number" min="1" name="amountToPay" id="amountToPay" class="pspf-input-amount-to-pay" placeholder="Enter amount" required=""></p>
    <p><input type="text" name="nickName" id="nickName" class="pspf-input-nickname" placeholder="Nickname / e-mail" required=""></p>
    <p><input type="submit" id="submit_btn" class="pspf-input-submit-button" name="submitted" value="Submit payment"></p>
    <div class="pspf-div-payment-systems-logos">
    <img src="/wp-content/plugins/payze-simple-payment-form/public/img/visa.png" alt="VISA logo">
    <img class="pspf-img-payment-system-logo" src="/wp-content/plugins/payze-simple-payment-form/public/img/mastercard.png" alt="MASTERCSARD logo">
    <img class="pspf-img-payment-system-logo" src="/wp-content/plugins/payze-simple-payment-form/public/img/payze_new_logo_nb.png" alt="PAYZE logo">
    <img class="pspf-img-payment-system-logo" src="/wp-content/plugins/payze-simple-payment-form/public/img/bog.png" alt="Bank of Georgia logo"></div></form>';

return $payment_form_html;