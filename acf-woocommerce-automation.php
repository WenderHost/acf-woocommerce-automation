<?php
/**
 * Plugin Name:     ACF WooCommerce Automation
 * Plugin URI:      https://github.com
 * Description:     Automate tasks in association with WooCommerce orders. Requires Advanced Custom Fields Pro.
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     acf-woocommerce-automation
 * Domain Path:     /languages
 * Version:         1.0.3
 *
 * @package         Acf_Woocommerce_Automation
 */

// Your code starts here.
$css_dir = ( stristr( site_url(), '.local' ) || SCRIPT_DEBUG )? 'css' : 'dist' ;
define( 'ACF_WCA_CSS_DIR', $css_dir );
define( 'ACF_WCA_DEV_ENV', stristr( site_url(), '.local' ) );

// Load Composer Modules
require_once( 'vendor/autoload.php' );

// Include required files
require_once( 'lib/fns/acf.php' );
require_once( 'lib/fns/acf-mailpoet-list-select.php' );
require_once( 'lib/fns/acf-json-save-point.php' );
require_once( 'lib/fns/automations.php' );
require_once( 'lib/fns/handlebars.php' );
require_once( 'lib/fns/utilities.php' );

/**
 * Enhanced logging
 *
 * @param      string  $message  The message
 */
if( ! function_exists( 'uber_log') ){
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}