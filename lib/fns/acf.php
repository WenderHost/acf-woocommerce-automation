<?php

namespace ACFWCA\acf;

function add_acf_option_pages(){
  if( function_exists('acf_add_options_page') ) {
    \acf_add_options_page([
      'page_title'  => 'General',
      'menu_title'  => 'ACF WC Automation',
      'menu_slug'   => 'acf-wca',
      'capability'  => 'edit_posts',
      'redirect'    => false,
      'icon_url'    => 'dashicons-admin-settings',
    ]);

    \acf_add_options_sub_page([
      'page_title'  => 'Automations',
      'menu_title'  => 'Automations',
      'parent_slug' => 'acf-wca',
    ]);
  }
}
add_action('acf/init', __NAMESPACE__ . '\\add_acf_option_pages');