<?php

	/* With help from http://code.tutsplus.com/tutorials/creating-customized-comment-emails-building-a-plugin--wp-28681
  * http://www.artishock.net/wordpress/how-to-change-wordpress-default-email-from-name-and-from-address/
	*/

	class Patched_Up_Email_Formatter {

		function __construct() {
      add_filter( 'wp_mail_from', function($email) {
        return get_option('admin_email');
      });
  
			add_filter( 'comment_moderation_headers', array( $this, 'email_headers' ) );
			add_filter( 'comment_moderation_subject', array( $this, 'email_subject' ), 10, 2 );
			add_filter( 'comment_moderation_text', array( $this, 'email_text' ), 10, 2 );
		}

		function email_headers(){
			add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
		}

		function email_subject( $subject, $comment_id ) {
			$comment = get_comment( $comment_id );
      $post    = get_post( $comment->comment_post_ID );

      // The blogname option is escaped with esc_html on the way into the database in sanitize_option
      // we want to reverse this for the plain text arena of emails.
      $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

      $subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );

      return $subject;
		}

		function email_text( $message, $comment_id ) {
      global $wpdb;

			$comment = get_comment( $comment_id );
      $post    = get_post( $comment->comment_post_ID );
      $author  = get_userdata( $post->post_author );

      $comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
      $comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");

      $approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment_id" ) );
      $approval_url = home_url( '/' ) . 
                      "wp-admin/comment.php?c=$comment->comment_ID" . 
                      "&action=approve";

      $script =					
				'<script type="application/ld+json">
          {
            "@context": "http://schema.org",
            "@type": "EmailMessage", 
            "action": {
              "@type": "ViewAction",
              "name": "Approve Comment",
              "url": "' . $approval_url . '"
            },
            "description": "Approval request for SOME COMMENT" 
          } 
        </script>'; 


      // Taken directly from /wp-include/pluggable.php then transcribed a bit into html
      $notify_message  = sprintf( __('A new comment on the post "%s" is waiting for your approval'), $post->post_title ) . "<br />";
      $notify_message .= get_permalink($comment->comment_post_ID) . "<br /><br />";
      $notify_message .= sprintf( __('Author : %1$s (IP: %2$s , %3$s)'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "<br />";
      $notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "<br />";
      $notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "<br />";
      $notify_message .= sprintf( __('Whois  : http://whois.arin.net/rest/ip/%s'), $comment->comment_author_IP ) . "<br />";
      $notify_message .= __('Comment: ') . "<br />" . $comment->comment_content . "<br /><br />";
      $notify_message .= sprintf( __('Approve it: %s'),  admin_url("comment.php?action=approve&c=$comment_id") ) . "<br />";

      if ( EMPTY_TRASH_DAYS )
        $notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "<br />";
      else
        $notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "<br />";
      $notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "<br />";

      $notify_message .= sprintf( _n('Currently %s comment is waiting for approval. Please visit the moderation panel:',
        'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting), number_format_i18n($comments_waiting) ) . "<br />";
      $notify_message .= admin_url("edit-comments.php?comment_status=moderated") . "<br />";

			$body = '<html>
								<body>' .
                  $script . 
                  $notify_message .
								'</body>
							</html>';


			return $body;

		}
	}
?>
