<?php

namespace ACFWCA\automations;

/**
 * Determines if our automation applies to any items in this order.
 *
 * @param      int      $automation_product_id  The automation product ID
 * @param      array    $order_items            The order items
 *
 * @return     boolean  True if automation, False otherwise.
 */
function has_automation( $automation_product_id, $order_items ){
  return in_array( $automation_product_id, $order_items );
}

/**
 * Runs our automations during `woocommerce_payment_complete`
 *
 * @param      int   $order_id  The order ID
 *
 * @return     boolean  ( description_of_the_return_value )
 */
function payment_complete( $order_id ){
    $order = wc_get_order( $order_id );
    $billing_email = $order->get_billing_email();
    $items = $order->get_items();
    $order_items = [];
    foreach( $items as $item ){
      //uber_log('🔔 $item = ' . print_r( $item, true ) );
      //wp_die();
      $order_items[] =  $item->get_product_id();
      uber_log('🔔 Product Name = ' . $item->get_name() );
      uber_log('🔔 Product ID = ' . $item->get_product_id() );
    }

    // Exit if no items in Order
    if( empty( $order_items ) )
      return false;

    // Get the user associated with the order. False for guests.
    $user = $order->get_user();
    $customer = [];
    if( $user ){
      $customer['first_name'] = get_user_meta( $user->ID, 'first_name', true );
      $customer['last_name'] = get_user_meta( $user->ID, 'last_name', true );
      $customer['email'] = $user->data->user_email;
    } else {
      $customer['first_name'] = $order->get_billing_first_name();
      $customer['last_name'] = $order->get_billing_last_name();
      $customer['email'] = $order->get_billing_email();
    }

    // Get our global automation options.
    $default_from_name = get_field( 'default_from_name', 'option' );
    if( empty( $default_from_name ) )
      $default_from_name = get_bloginfo( 'name' );
    $default_from_email = get_field( 'default_from_email', 'option' );
    if( ! is_email( $default_from_email ) )
      $default_from_email = get_bloginfo( 'admin_email' );

    // Process any automations we've defined
    if( \have_rows( 'automations', 'option' ) ){
      while( \have_rows( 'automations', 'option' ) ): the_row();
        $automation = \get_sub_field( 'automation' );
        //uber_log('🔔 $automation = ' . print_r( $automation, true ) );
        $actions = $automation['actions'];
        foreach ( $actions as $key => $action_array ) {
          $action = $action_array['acf_fc_layout'];
          switch( $action ){
            case 'subscribe':
              if( has_automation( $automation['product'], $order_items ) )
                require( plugin_dir_path( __FILE__ ) . 'automations.subscribe.php' );
              break;

            case 'email':
              if( has_automation( $automation['product'], $order_items ) )
                require( plugin_dir_path( __FILE__ ) . 'automations.email.php' );
              break;
          }
        }
      endwhile;
    }

    /*
    $user = $order->get_user();
    if( $user ){
        // do something with the user
    }
    */
}
add_action( 'woocommerce_payment_complete', __NAMESPACE__ . '\\payment_complete' );