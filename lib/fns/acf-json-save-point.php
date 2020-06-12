<?php

namespace ACFWCA\acf;

/**
 * Saves ACF configurations as JSON.
 *
 * @param      string  $path   The save path
 *
 * @return     string  The modified save path.
 */
function acf_json_save_point( $path ) {
  // update path
  $path = plugin_dir_path( __FILE__ ) . '../acf-json';

  // return
  return $path;
}
add_filter('acf/settings/save_json', __NAMESPACE__ . '\\acf_json_save_point');

/**
 * Loads ACF JSON Configuration files from our save path.
 *
 * @param      array  $paths  The array containing our ACF save paths
 *
 * @return     array  Filtered ACF save paths array.
 */
function acf_json_load_point( $paths ) {
    // remove original path
    unset($paths[0]);

    // append path
    $paths[] = plugin_dir_path( __FILE__ ) . '../acf-json';

    // return
    return $paths;
}
add_filter('acf/settings/load_json', __NAMESPACE__ . '\\acf_json_load_point');