<?php


class Patched_Up_Email_Formatter_Options_Page {

  function __construct() {
    wp_enqueue_style( 'patched_up_email_formatter_style', plugins_url('/', __FILE__) . 'style.css' );
    wp_enqueue_script( 'patched_up_email_formatter_script', plugins_url('/', __FILE__) . 'script.js' );
    wp_localize_script( 'patched_up_email_formatter_script', 'ajax_object',
                  array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  
    
    $this->options_page_markup();
  }

  private function options_page_markup() {
    global $patched_up_comment_approval_email;
    
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
              To protect its users from spammers, Gmail will not render the quick-action buttons from unknown emails.
              However, any email sent from itself will render the quick-action buttons, as a way of testing the system. 
            </p>';
    echo   '<p>
              Once you are ready launch the system, you must register through Google:
              <a href="https://developers.google.com/gmail/actions/registering-with-google" target="_blank">Register with Google</a> 
            </p>';

    echo   '<h3>Settings</h3>';
    echo   '<p>
              Change the admin email on the <a href="/wp-admin/options-general.php">General Settings Page.</a>
            </p>';
    echo   '<p>
              Change the conditions for when to send an email on the <a href="/wp-admin/options-discussion.php">Discussion Settings Page.</a>
            </p>';

    echo   '<h3>Sample Email Markup</h3>';
    echo   $patched_up_comment_approval_email->sample_markup();

    echo   '<h3>Test Email</h3>';
    $last_comment             = get_last_comment();
    $last_comment_id          = $last_comment->comment_ID;
    $last_comment_post_id     = $last_comment->comment_post_ID;
    $last_comment_post_title  = get_post($last_comment_post_id)->post_title;
    echo   '<p>
              Press the button to send a test email to you administrator email.
            </p>
            <p>
              Will send test approval email to <span class="code">' . get_option('admin_email') .'</span>
              for the last unapproved comment on this WordPress site.
              <br /><br />';
    if( ! $last_comment )
      echo   'It looks like there are no unapproved comments, so expect those fields to be blank.
              <br />
              We will send the test email anyway though.';
    else
      echo   'It looks like that comment is <span class="code">#' .
              $last_comment_id . '</span> on the post <span class="code">"' . 
              $last_comment_post_title . '"</span>.';

    echo     '<br />
              <br />
              
              <input type="button" id="send-test-email" class="button-primary button" value="Send Test Email" />
              <a class="button" title="Change email on General Settings page" href="options-general.php">Change Email</a>
            </p>';

    echo '</section>';
  }

}


