<?php
/*
Plugin Name: Patched Up Email Formatter
Version: 0.1.0
Description: A quick email formatter
Author: Casey Patrick Driscoll
Author URI: http://caseypatrickdriscoll.com
Plugin URI: 
Text Domain: patched-up-email-formatter
Domain Path: /languages
*/

include_once 'class-patched-up-email-formatter.php';

/*
Plugin Name: Email Return Path Fix
Author: Abdussamad Abdurrazzaq
Plugin URI: http://abdussamad.com/archives/567-Fixing-the-WordPress-Email-Return-Path-Header.html
*/
class email_return_path {
    function __construct() {
    add_action( 'phpmailer_init', array( $this, 'fix' ) );    
    }
 
  function fix( $phpmailer ) {
      $phpmailer->Sender = $phpmailer->From;
  }
}
 
new email_return_path();

new Patched_Up_Email_Formatter();

?>
