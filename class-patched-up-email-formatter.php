<?php

	/* With help from http://code.tutsplus.com/tutorials/creating-customized-comment-emails-building-a-plugin--wp-28681
  * http://www.artishock.net/wordpress/how-to-change-wordpress-default-email-from-name-and-from-address/
	*/

	class Patched_Up_Email_Formatter {

		function __construct() {
      add_filter( 'wp_mail_from', function($email) {
        return 'caseypatrickdriscoll@gmail.com';
      });
      add_filter( 'wp_mail_from_name', function($name) {
        return 'Casey Patrick Driscoll';
      });
  
			// Moderation
			add_filter( 'comment_moderation_headers', array( $this, 'email_headers' ) );
			add_filter( 'comment_moderation_subject', array( $this, 'email_subject' ), 10, 2 );
			add_filter( 'comment_moderation_text', array( $this, 'email_text' ), 10, 2 );

			// Notifications
			add_filter( 'comment_notification_headers', array( $this, 'email_headers' ) );
			add_filter( 'comment_notification_subject', array( $this, 'email_subject' ), 10, 2 );
			add_filter( 'comment_notification_text', array( $this, 'email_text' ), 10, 2 );
				 
		}

		function email_headers(){
			add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
		}

		function email_subject( $subject, $comment_id ) {
      $subject = "New comment on: ";

			$comment = get_comment( $comment_id );
    	$subject .= get_the_title( $comment->comment_post_ID );

      return $subject;
		}

		function email_text( $message, $comment_id ) {

			$comment = get_comment( $comment_id );

      $approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment_id" ) );
      $approval_url = home_url( '/' ) . 
                      "wp-admin/comment.php?c=$comment->comment_ID" . 
                      "&action=approvecomment&$approve_nonce";

      $script =					
        '{
          "@context": "http://schema.org",
          "@type": "EmailMessage", 
          "action": {
            "@type": "ConfirmAction",
            "name": "Approve Comment",
            "handler": {
              "@type": "HttpActionHandler",
              "url": "' . $approval_url . '"
            }
          },
          "description": "Approval request for SOME COMMENT" 
        }'; 

      $script = 
        '{
          "@context":              "http://schema.org",
          "@type":                 "EventReservation",
          "reservationNumber":     "IO12345",
          "underName": {
            "@type":               "Person",
            "name":                "John Smith"
          },
          "reservationFor": {
            "@type":               "Event",
            "name":                "Google I/O 2014",
            "startDate":           "2014-05-15T08:30:00-08:00",
            "location": {
              "@type":             "Place",
              "name":              "Moscone Center",
              "address": {
                "@type":           "PostalAddress",
                "streetAddress":   "800 Howard St.",
                "addressLocality": "San Francisco",
                "addressRegion":   "CA",
                "postalCode":      "94103",
                "addressCountry":  "US"
              }
            }
          }
        }';

			$body = '<html>
								<body>
									<script type="application/ld+json">' .
                    $script . '
                  </script>
									<h1>New comment</h1>' .
									"<p>hi you have a new message: $comment->comment_content</p>" . 
                  "<a href='$approval_url'>$approval_url</a>" .
                  "<pre>$script</pre>" . 
								'</body>
							</html>';


			return $body;

		}
	}
?>
