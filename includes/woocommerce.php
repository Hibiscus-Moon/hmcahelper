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
/*   echo '<div style="display: inline-block; width: 80%;"><img src="/wp-content/themes/hibiscusmoon/img/credit_cards_2.png"/></div>';*/
   echo '</div>';
}




// =============================================================================
// WooCommerce - Referral Select Field in Checkout
// =============================================================================
add_action( 'woocommerce_after_checkout_billing_form', 'referral_checkout_field' );
function referral_checkout_field( $checkout ) {
    $is_in_cart = false;

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( has_term( 'certified-crystal-healer', 'product_cat', $cart_item['product_id'] ) ) {
            $is_in_cart = true;
            break;
        }
    }

    if ( $is_in_cart ) {

        echo '<div id="referral_checkout_field"><label>' . __('How did you hear about us?') . '</label>';

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




// =============================================================================
 // Remove the "Cancel" button from the My Subscriptions table for CCH Payment Plan users
// =============================================================================
function eg_remove_my_subscriptions_button( $actions, $subscription ) {

    $currentuser = wp_get_current_user(); 
    $currentuserid = $currentuser->ID;
    $currentuseremail = $currentuser->user_email; 

    foreach ( $actions as $action_key => $action ) {
        if(wcs_user_has_subscription($currentuserid, '27822', 'any') ) {
            switch ( $action_key ) {
    //          case 'change_payment_method':   // Hide "Change Payment Method" button?
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
        else if (wcs_user_has_subscription($currentuserid, '49952', 'any') ) {
            switch ( $action_key ) {
    //          case 'change_payment_method':   // Hide "Change Payment Method" button?
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
    }
    return $actions;
    
}
add_filter( 'wcs_view_subscription_actions', 'eg_remove_my_subscriptions_button', 100, 2 );




// =============================================================================
// WooCommerce - Custom CCH Email Header
// =============================================================================
function woocommerce_custom_cch_receipt( $email_heading, $order ) {
    global $woocommerce;
    $items = $order->get_items();
    $cchproducts = array('27820', '27821', '27822', '49951', '49952');


    foreach ( $items as $item ) {
        $product_id = $item['product_id'];
        if ( in_array($product_id, $cchproducts)) {
            $email_heading = 'Thank you for registering!';
        }
        return $email_heading;
    }
}
add_filter( 'woocommerce_email_heading_customer_processing_order', 'woocommerce_custom_cch_receipt', 10, 5 );




// =============================================================================
// Woocommerce - Custom CCH Email Subject Line
// =============================================================================
function woocommerce_customer_cch_email_subject( $subject, $order ) {
    global $woocommerce;
    $items = $order->get_items();
    $cchproducts = array('27820', '27821', '27822', '49951', '49952');


    foreach ( $items as $item ) {
        $product_id = $item['product_id'];
         if ( in_array($product_id, $cchproducts)) {
            $subject = sprintf( 'Congrats! Your registration is complete!' );
        }
        return $subject;
    }

}
add_filter('woocommerce_email_subject_customer_processing_order', 'woocommerce_customer_cch_email_subject', 1, 2);













/**
 * Restricts which products can be added to the cart at the same time.
 * Version for WooCommerce 3.x and later.
 *
 * HOW TO USE THIS CODE
 * 1. Add the code to the bottom of your theme's functions.php file (see https://www.skyverge.com/blog/add-custom-code-to-wordpress/).
 * 2. Set the IDs of the products that are allowed to be added to the cart at the same time.
 * 3. Amend the message displayed to customers when products are unavailable after the specified
 *    products have been added to the cart (see function woocommerce_get_price_html(), below).
 *
 * GPL DISCLAIMER
 * Because this code program is free of charge, there is no warranty for it, to the extent permitted by applicable law.
 * Except when otherwise stated in writing the copyright holders and/or other parties provide the program "as is"
 * without warranty of any kind, either expressed or implied, including, but not limited to, the implied warranties of
 * merchantability and fitness for a particular purpose. The entire risk as to the quality and performance of the program
 * is with you. should the program prove defective, you assume the cost of all necessary servicing, repair or correction.
 *
 * Need a consultation, or assistance to customise this code? Find us on Codeable: https://aelia.co/hire_us
 */
 
/**
 * Retrieves the cart contents. We can't just call WC_Cart::get_cart(), because
 * such method runs multiple actions and filters, which we don't want to trigger
 * at this stage.
 *
 * @author Aelia <support@aelia.co>
 */
function aelia_get_cart_contents() {
  $cart_contents = array();
  /**
   * Load the cart object. This defaults to the persistant cart if null.
   */
  $cart = WC()->session->get( 'cart', null );
 
    if ( is_null( $cart ) && ( $saved_cart = get_user_meta( get_current_user_id(), '_woocommerce_persistent_cart_' . get_current_blog_id(), true ) ) ) { // @codingStandardsIgnoreLine
        $cart                = $saved_cart['cart'];
    }
    elseif ( is_null( $cart ) ) {
        $cart = array();
    }
    elseif ( is_array( $cart ) && ( $saved_cart = get_user_meta( get_current_user_id(), '_woocommerce_persistent_cart_' . get_current_blog_id(), true ) ) ) { // @codingStandardsIgnoreLine
        $cart                = array_merge( $saved_cart['cart'], $cart );
    }
 
  if ( is_array( $cart ) ) {
    foreach ( $cart as $key => $values ) {
      $_product = wc_get_product( $values['variation_id'] ? $values['variation_id'] : $values['product_id'] );
 
      if ( ! empty( $_product ) && $_product->exists() && $values['quantity'] > 0 ) {
        if ( $_product->is_purchasable() ) {
          // Put session data into array. Run through filter so other plugins can load their own session data
          $session_data = array_merge( $values, array( 'data' => $_product ) );
          $cart_contents[ $key ] = apply_filters( 'woocommerce_get_cart_item_from_session', $session_data, $values, $key );
        }
      }
    }
  }
  return $cart_contents;
}
 
// Step 1 - Keep track of cart contents
add_action('wp_loaded', function() {
  // If there is no session, then we don't have a cart and we should not take
  // any action
  if(!is_object(WC()->session)) {
    return;
  }
 
  // This variable must be global, we will need it later. If this code were
  // packaged as a plugin, a property could be used instead
  global $allowed_cart_items;
  // We decided that products with ID 737 and 832 can go together. If any of them
  // is in the cart, all other products cannot be added to it
  global $restricted_cart_items;
  $restricted_cart_items = array(
        // Set the IDs of the products that can be added to the cart at the same time
    52018
  );
 
  // "Snoop" into the cart contents, without actually loading the whole cart
  foreach(aelia_get_cart_contents() as $item) {
    if(in_array($item['data']->get_id(), $restricted_cart_items)) {
      $allowed_cart_items[] = $item['data']->get_id();
 
      // If you need to allow MULTIPLE restricted items in the cart, comment
      // the line below
      break;
    }
  }
 
  // Step 2 - Make disallowed products "not purchasable"
  add_filter('woocommerce_is_purchasable', function($is_purchasable, $product) {
    global $restricted_cart_items;
    global $allowed_cart_items;
 
    // If any of the restricted products is in the cart, any other must be made
    // "not purchasable"
    if(!empty($allowed_cart_items)) {
      // To allow MULTIPLE products from the restricted ones, use the line below
      //$is_purchasable = in_array($product->id, $allowed_cart_items) || in_array($product->id, $restricted_cart_items);
 
      // To allow a SINGLE  products from the restricted ones, use the line below
      $is_purchasable = in_array($product->get_id(), $allowed_cart_items);
    }
    return $is_purchasable;
  }, 10, 2);
}, 10);
 
// Step 3 - Explain customers why they can't add some products to the cart
add_filter('woocommerce_get_price_html', function($price_html, $product) {
  if(!$product->is_purchasable() && is_product()) {
    $price_html .= '<p>' . __('This product cannot be purchased together with other products. If you wish to buy this product, please remove the other products from the cart.', 'woocommerce') . '</p>';
  }
  return $price_html;
}, 10, 2);