<?php



// =============================================================================
// WooCommerce Custom Order Confirmation Text
// =============================================================================

add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2 );
function woo_change_order_received_text( $str, $order ) {
    $new_str = $str . ' Now keep your eyes on your inbox for your receipt as well as details on how to access your purchase.';
    return $new_str;
}







// =============================================================================
// WooCommerce - Autocomplete orders on payment
// =============================================================================
add_action( 'woocommerce_order_status_processing', 'processing_to_completed');

function processing_to_completed($order_id){

    $order = new WC_Order($order_id);
    $order->update_status('completed'); 

}





// Removes Order Notes Title - Additional Information & Notes Field
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );



// Remove Order Notes Field
add_filter( 'woocommerce_checkout_fields' , 'remove_order_notes' );

function remove_order_notes( $fields ) {
     unset($fields['order']['order_comments']);
     return $fields;
}





// =============================================================================
// WooCommerce - Credit Card Icons in Checkout
// =============================================================================
add_action( 'woocommerce_review_order_before_payment', 'hmca_authorize_seal' );
 
function hmca_authorize_seal() {
   echo '<div style="display: table; width: 100%; margin: 15px auto; text-align: center;">';
   echo '<div class="AuthorizeNetSeal" style="display: inline-block; width: 18%;"><script type="text/javascript" language="javascript">var ANS_customer_id="56cccdb3-51bd-48fc-8869-6315dbc3cac1";</script> <script type="text/javascript" language="javascript" src="//verify.authorize.net:443/anetseal/seal.js" ></script> </div>';
   echo '<div style="display: inline-block; width: 80%;"><img src="/wp-content/themes/x-child-integrity-light/images/credit_cards_2.png"/></div>';
   echo '</div>';
}




// =============================================================================
// WooCommerce - Referral Select Field in Checkout
// =============================================================================
add_action( 'woocommerce_after_order_notes', 'referral_checkout_field' );
function referral_checkout_field( $checkout ) {
    $is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {

        echo '<div id="referral_checkout_field"><h2>' . __('How did you hear about us?') . '</h2>';

        woocommerce_form_field( 'referral_select', array(
            'type'          => 'select',
            'class'         => array('form-row-wide'),
            'label'         => __(''),
            'placeholder'   => __(''),
            'required'		=> false,
            'options'		=> array( 
            					'' => 'Select...', 
            					'google' => 'Google',
            					'facebook' => 'Facebook',
            					'instagram' => 'Instagram',
            					'pinterest' => 'Pinterest',
            					'youtube' => 'YouTube',
            					'magspiritual' => 'Magazine: Spirituality & Health',
            					'magreiki' => 'Magazine: Reiki',
            					'personal' => 'Personal referral',
            					'other' => 'Other',
            				)
            ), $checkout->get_value( 'referral_select' ));

        echo '</div>';
    }
}

/*add_action('woocommerce_checkout_process', 'referral_checkout_field_process');
function referral_checkout_field_process() {
    $is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {
        // Check if set, if its not set add an error.
        if ( ! $_POST['referral_select'] )
            wc_add_notice( __( 'Please tell us how you heard about Hibiscus Moon.' ), 'error' );
    }
}*/

add_action( 'woocommerce_checkout_update_order_meta', 'referral_checkout_field_update_order_meta' );
function referral_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['referral_select'] ) ) {
        update_post_meta( $order_id, 'Referral', sanitize_text_field( $_POST['referral_select'] ) );
    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'referral_checkout_field_display_admin_order_meta', 10, 1 );
function referral_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Referral').':</strong> ' . get_post_meta( $order->id, 'Referral', true ) . '</p>';
}




/*add_action( 'woocommerce_after_order_notes', 'referral_other_checkout_field' );
function referral_other_checkout_field( $checkout ) {
    $is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {

        woocommerce_form_field( 'referral_other', array(
            'type'          => 'text',
            'class'         => array('form-row-wide'),
            'label'         => __('If other, where did you hear about us?'),
            'placeholder'   => __(''),
            'required'		=> false,
            ), $checkout->get_value( 'referral_other' ));
    }

}

add_action( 'woocommerce_checkout_update_order_meta', 'referral_other_checkout_field_update_order_meta' );
function referral_other_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['referral_other'] ) ) {
        update_post_meta( $order_id, 'Referral Other', sanitize_text_field( $_POST['referral_other'] ) );
    }
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'referral_other_checkout_field_display_admin_order_meta', 10, 1 );
function referral_other_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Other referral').':</strong> ' . get_post_meta( $order->id, 'Referral Other', true ) . '</p>';
}
*/



// =============================================================================
// WooCommerce - CCH Terms and Conditions Checkbox
// =============================================================================
add_filter( 'woocommerce_checkout_after_customer_details', 'conditional_checkout_fields_products' );
function conditional_checkout_fields_products( $fields ) {
    $is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {

	    echo '<div id="my_custom_checkout_field">';

	    woocommerce_form_field( 'my_field_name', array(
	        'type'      => 'checkbox',
	        'class'     => array('input-checkbox'),
	        'label'         => __('Checking this box indicates that you agree to the <a href="https://hibiscusmooncrystalacademy.com/welcome/enrollment-policies-student-waiver-form/" target="_blank">Registration Policies and Student Waiver</a>.'),
	        'required'  => true,
	    ),  WC()->checkout->get_value( 'my_field_name' ) );
	    echo '</div>';
	    }

    return $fields;
}


// Show notice if customer does not tick
add_action( 'woocommerce_checkout_process', 'hmca_cch_not_approved_terms' );
function hmca_cch_not_approved_terms( $fields ) {

	$is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {

	    if ( ! (int) isset( $_POST['my_field_name'] ) ) {
	        wc_add_notice( __( 'Please acknowledge the Terms and Conditions' ), 'error' );
	    }

	}

	 return $fields;
}





// =============================================================================
// WooCommerce - Required Field Text
// =============================================================================
add_action( 'woocommerce_checkout_after_customer_details', 'hmca_requiredfield_text' );
 
function hmca_requiredfield_text() {
   echo '<p style="margin-bottom: 50px;"><strong>* - required info</strong></p>';
}







// =============================================================================
// WooCommerce - Login Disclaimer
// =============================================================================
add_action( 'woocommerce_before_checkout_form', 'hmca_checkout_login_disclaimer' );
 
function hmca_checkout_login_disclaimer() {

	if ( !is_user_logged_in() ) {
		echo '<p><strong>NOTE: If you’ve made a purchase with HMCA recently (Since June of 2019) you already have an account, so please enter your login details below. If you are a student or member of our Crystal Community who has not made a purchase since June 2019, please proceed to the Billing Section.</strong></p>';
	}
}






/**
 * Remove the "Cancel" button from the My Subscriptions table for CCH Payment Plan users
 *
 */
function eg_remove_my_subscriptions_button( $actions, $subscription ) {

    $currentuser = wp_get_current_user(); 
    $currentuserid = $currentuser->ID;
    $currentuseremail = $currentuser->user_email; 

    if(wcs_user_has_subscription($currentuserid, '27822', 'any') ) {

        foreach ( $actions as $action_key => $action ) {
            switch ( $action_key ) {
/*                case 'change_payment_method':   // Hide "Change Payment Method" button?*/
    //          case 'change_address':      // Hide "Change Address" button?
    //          case 'switch':          // Hide "Switch Subscription" button?
    //          case 'resubscribe':     // Hide "Resubscribe" button from an expired or cancelled subscription?
    //          case 'pay':         // Hide "Pay" button on subscriptions that are "on-hold" as they require payment?
    //          case 'reactivate':      // Hide "Reactive" button on subscriptions that are "on-hold"?
             case 'cancel':          // Hide "Cancel" button on subscriptions that are "active" or "on-hold"?
                    unset( $actions[ $action_key ] );
                    break;
                default: 
                    error_log( '-- $action = ' . print_r( $action, true ) );
                    break;
            }
        }
        return $actions;

    }
}
add_filter( 'wcs_view_subscription_actions', 'eg_remove_my_subscriptions_button', 100, 2 );