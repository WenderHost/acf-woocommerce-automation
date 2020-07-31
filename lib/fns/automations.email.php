<?php
use function ACFWCA\utilities\{get_store_address};
use function ACFWCA\handlebars\{render_template};

uber_log('🔔 Running `email` automation...');

$recipient = $action_array['recipient'];
$subject = $action_array['subject'];
$message = $action_array['message'];

// Replace our tokens in $recipient.
$search = [ '{customer_email}', '{admin_email}' ];
$replace = [ $customer['email'], get_bloginfo( 'admin_email' ) ];
$recipient = str_replace( $search, $replace, $recipient );

// Convert the tokens in the message to HBS variables.
$search = [ '{customer_name}', '{customer_email}' ];
$replace = [ '{{customer_name}}', '{{customer_email}}' ];
$message = str_replace( $search, $replace, $message );

// Render our $message as a Handlebars template.
$message = render_template([
  'template'        => $message,
  'customer_name'   => $customer['first_name'] . ' ' . $customer['last_name'],
  'customer_email'  => $customer['email'],
]);

// Get the site logo
$logo = get_custom_logo();
if( ! $logo ){
  $logo = '<h1>' . get_bloginfo( 'name' ) . '</h1>';
} else {
  preg_match('/src="(.*)"/mU', $logo, $matches );
  $logo = '<a href="' . get_bloginfo( 'url' ) . '"><img src="' . $matches[1] . '" style="max-width: 320px; height: auto;" /></a>';
}

// Get our email template, add the $message, and render it as a Handlebars template.
$email_template = file_get_contents( plugin_dir_path( __FILE__ ) . '../templates/email-template.html' );
$html_message = render_template([
  'template'        => $email_template,
  'store_name'      => get_bloginfo( 'name' ),
  'store_address'   => get_store_address(),
  'message'         => $message,
  'logo'            => $logo,
  'preheader_text'  => '',
]);

$headers = [
  'Content-Type: text/html; charset=UTF-8',
  'From: ' . $default_from_name . ' <' . $default_from_email . '>'
];

$send_bccs = get_field( 'send_bccs', 'option' );
$global_bcc = get_field( 'global_bcc', 'option' );
if(
  $send_bccs
  && 'Yes' == $send_bccs[0]
  && $global_bcc
  && is_email( $global_bcc )
){
  $headers[] = 'Bcc: ' . $global_bcc;
}
wp_mail( $recipient, $subject, $html_message, $headers );