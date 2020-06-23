<?php

namespace ACFWCA\utilities;

function get_store_address(){
  // The main address pieces:
  $store_address_1   = get_option( 'woocommerce_store_address' );
  $store_address_2   = get_option( 'woocommerce_store_address_2' );
  $store_city        = get_option( 'woocommerce_store_city' );
  $store_postcode    = get_option( 'woocommerce_store_postcode' );

  // The country/state
  $store_raw_country = get_option( 'woocommerce_default_country' );

  // Split the country/state
  $split_country = explode( ":", $store_raw_country );

  // Country and state separated:
  $store_country = $split_country[0];
  $store_state   = $split_country[1];

  $address['address_1'] = $store_address_1;
  if( $store_address_2 )
    $address['address_2'] = $store_address_2;
  $address['city_state_zip'] = $store_city . ', ' . $store_state . ' ' . $store_postcode;
  $address['country'] = $store_country;

  return implode( ', ', $address );
}