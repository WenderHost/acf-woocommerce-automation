<?php
use function ACFWCA\utilities\{get_store_address};
use function ACFWCA\handlebars\{render_template};

uber_log('🔔 Running `email` automation...');
//uber_log('🔔 $action_array = ' . print_r( $action_array, true ) );

$recipient = $action_array['recipient'];
$subject = $action_array['subject'];
$message = $action_array['message'];

$message = render_template([
  'template'        => $message,
  'customer_name'   => $customer['first_name'] . ' ' . $customer['last_name'],
  'customer_email'  => $customer['email'],
]);

$logo = get_custom_logo();
if( ! $logo ){
  $logo = '<h1>' . get_bloginfo( 'name' ) . '</h1>';
} else {
  preg_match('/src="(.*)"/mU', $logo, $matches );
  $logo = '<a href="' . get_bloginfo( 'url' ) . '"><img src="' . $matches[1] . '" style="max-width: 320px; height: auto;" /></a>';
}
uber_log('🔔 $logo = ' . $logo );

$email_template = file_get_contents( plugin_dir_path( __FILE__ ) . '../templates/email-template.html' );
$html_message = render_template([
  'template'        => $email_template,
  'store_name'      => get_bloginfo( 'name' ),
  'store_address'   => get_store_address(),
  'message'         => $message,
  'logo'            => $logo,
]);

$headers = array('Content-Type: text/html; charset=UTF-8');
wp_mail( $recipient, $subject, $html_message, $headers );