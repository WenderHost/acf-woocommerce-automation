<?php

namespace ACFWCA\mailpoetListSelect;

function register_field_v5() {
    if ( ! class_exists(\MailPoet\API\API::class) )
      return false;

    include_once( plugin_dir_path( __FILE__ ) . '../classes/acf-mailpoet-list-select.php' );
    new \acf_mailpoet_list_select();
  }
add_action( 'acf/include_field_types', __NAMESPACE__ . '\\register_field_v5' ); //array( __CLASS__, 'register_field_v5' )