<?php

namespace ACFWCA\woocommerce;


function payment_complete( $order_id ){
    $order = wc_get_order( $order_id );
    $billing_email = $order->billing_email;
    $items = $order->get_items();
    /*
    $user = $order->get_user();
    if( $user ){
        // do something with the user
    }
    */
}
add_action( 'woocommerce_payment_complete', __NAMESPACE__ . '\\payment_complete' );