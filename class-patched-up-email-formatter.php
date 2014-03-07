<?php

	/* With help from http://code.tutsplus.com/tutorials/creating-customized-comment-emails-building-a-plugin--wp-28681
	*/

	class Patched_Up_Email_Formatter {

		function __construct() {
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

			$body = '<html>
								<body>
									<script type="application/ld+json">
										{
											"@context": "http://schema.org",
											"@type": "EmailMessage", 
											"action": {
												"@type": "ConfirmAction",
												"name": "Approve Comment",
												"handler": {
													"@type": "HttpActionHandler",
													"url": "https://myexpenses.com/approve?expenseId=abc123"
												}
											},
											"description": "Approval request for John\'s $10.13 expense for office supplies"
										} 
									</script>
									<h1>New comment</h1>' .
									"<p>hi you have a new message: $comment->comment_content</p>" . 
								'</body>
							</html>';

			return $body;

		}
	}
?>
