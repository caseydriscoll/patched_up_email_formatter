<?php
/*
Plugin Name: Patched Up Email Formatter
Version: 0.1.2
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
 

new Patched_Up_Email_Formatter();

?>
