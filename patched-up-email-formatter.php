<?php
/*
Plugin Name: Patched Up Email Formatter
Version: 0.2.0
Description: A quick email formatter
Author: Casey Patrick Driscoll
Author URI: http://caseypatrickdriscoll.com
Text Domain: patched-up-email-formatter
Domain Path: /languages
*/

include_once 'class-patched-up-email-formatter.php';

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
  echo '<h2>Email Formatter</h2>';
}

new Patched_Up_Email_Formatter();

?>
