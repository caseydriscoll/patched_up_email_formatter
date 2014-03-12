<?php
/*
Plugin Name: Patched Up Email Formatter
Version: 0.3.1
Description: A quick email formatter
Author: Casey Patrick Driscoll
Author URI: http://caseypatrickdriscoll.com
Text Domain: patched-up-email-formatter
*/

include_once 'class-patched-up-comment-approval-email.php';
include_once 'class-patched-up-email-formatter-options-page.php';

$patched_up_comment_approval_email = new Patched_Up_Comment_Approval_Email();

// Example taken from: http://abdussamad.com/archives/567-Fixing-the-WordPress-Email-Return-Path-Header.html
// Must correct email headers so Gmail with render buttons (only from authentic senders)
add_action( 'phpmailer_init', array( $this, function($phpmailer) { 
  $phpmailer->Sender = $phpmailer->From; 
}));

// Add settings link on plugin page
function patched_up_email_formatter_settings_link($links) { 
  $settings_link = '<a href="tools.php?page=patched-up-email-formatter">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'patched_up_email_formatter_settings_link' );
 
function patched_up_email_formatter_add_page(){
  add_management_page( 
    'Email Formatter',
    'Email Formatter',
    'Administrator',
    'patched-up-email-formatter',
    'patched_up_email_formatter_options'
  );
}
add_action( 'admin_menu', 'patched_up_email_formatter_add_page');

function patched_up_email_formatter_options(){
  new Patched_Up_Email_Formatter_Options_Page();
}

if ( ! function_exists('get_last_comment') ):
function get_last_comment() {
  $args = array(
    'status' => 'hold',
    'number' => '1'
  );
  return get_comments($args)[0];
}
endif;

add_action( 'wp_ajax_send_test_email', function() {
  wp_notify_moderator(get_last_comment()->comment_ID);
  die("Email successfully sent!");
});

?>
