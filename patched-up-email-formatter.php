<?php
/*
Plugin Name: Patched Up Email Formatter
Version: 0.2.1
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
  wp_enqueue_style( 'patched_up_email_formatter_style', plugins_url('/', __FILE__) . 'style.css' );

  echo '<section id="patched_up_email_formatter_options">';

  echo   '<h2>Email Formatter</h2>';

  echo   '<h3>Instructions</h3>';
  echo     '<p>
              Hello and welcome!
              This plugin adds schema to the comment approval emails sent to your admins.
              Gmail is able to read this schema and it creates \'quick-action\' buttons in the inbox,
              so you don\'t to open emails for trivial tasks, saving you clicks!
            </p>';
  echo     '<p>
              Be sure to read these posts for a quick primer on how the system works:
            </p>';
  echo     '<ul>
              <li><a href="http://gmailblog.blogspot.com/2013/05/take-action-right-from-inbox.html" target="_blank">Gmail Blog</a></li>
              <li><a href="https://developers.google.com/gmail/schemas/" target="_blank">Google Developers</a></li>
            </ul>';
  echo   '<p>
            Google makes it very easy to get started. 
            To protect its users from spammers, Gmail will not renderd the quick-action buttons from unknown emails.
            However, any email sent from itself will render the quick-action buttons, as a way of testing the system. 
          </p>';
  echo   '<p>
            Once you are ready launch the system, you must register through Google:
            <a href="https://developers.google.com/gmail/actions/registering-with-google" target="_blank">Register with Google</a> 
          </p>';

  echo '</section>';
}

new Patched_Up_Email_Formatter();

?>
