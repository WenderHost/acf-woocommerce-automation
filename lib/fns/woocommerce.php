<?php

namespace ACFWCA\woocommerce;


function payment_complete( $order_id ){
    $order = wc_get_order( $order_id );
    $billing_email = $order->billing_email;
    $items = $order->get_items();
    foreach( $items as $item ){
      uber_log('🔔 Product Name = ' . $item->get_name() );
      uber_log('🔔 Product ID = ' . $item->get_id() );
    }
    /*
    $user = $order->get_user();
    if( $user ){
        // do something with the user
    }
    */
}
add_action( 'woocommerce_payment_complete', __NAMESPACE__ . '\\payment_complete' );