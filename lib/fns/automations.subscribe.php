<?php
uber_log('🔔 Running `subscribe` automation...');
//uber_log('🔔 $action_array = ' . print_r( $action_array, true ) );

if (class_exists(\MailPoet\API\API::class)){
  $mailpoet_api = \MailPoet\API\API::MP('v1');
  $subscriber = [
    'email' => $billing_email,
  ];
  $list_ids = [ $action_array['mailpoet_list'] ];

  // Check if subscriber exists. If subscriber doesn't exist an exception is thrown
  try {
    $get_subscriber = $mailpoet_api->getSubscriber( $subscriber['email'] );
  } catch (\Exception $e) {}

  try {
    if ( ! $get_subscriber ) {
      // Subscriber doesn't exist let's create one
      $mailpoet_api->addSubscriber( $subscriber, $list_ids );
    } else {
      // In case subscriber exists just add him to new lists
      $mailpoet_api->subscribeToLists( $subscriber['email'], $list_ids );
    }
  } catch (\Exception $e) {
    $error_message = $e->getMessage();
    uber_log('🔔 MailPoet error_message: ' . $error_message );
  }
}