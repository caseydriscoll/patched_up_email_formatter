=== Patched Up Email Formatter ===
Contributors: caseypatrickdriscoll
Tags: email, gmail, quick, action, inbox
Requires at least: 3.8.1
Tested up to: 3.8.1
Version: 0.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A small plugin to format emails, particularily to leverage Gmail's quick action buttons.

== Description ==

This plugin formats WordPress comment approval emails to take advantage of Gmail's quick-action
buttons.

http://gmailblog.blogspot.com/2013/05/take-action-right-from-inbox.html
https://developers.google.com/gmail/schemas/

The overall formatted text of the email is unchanged from the standard WordPress email text (the 
copy is identical infact), excepting that it is converted to an HTML email to allow for the schema
implementation.

Ideally, the plugin implementation would be a 'one-click' solution. Meaning, using the schema 
for "ConfirmAction", the user would be able to do a one-click comment approval from the inbox,
without going into the body of the email. However, this solution requires a HTTP request from
Google's notification servers, and as far as I can tell, there is no way to get around the
wp-admin authentication requirement. Any request to a wp-admin page gets immediately redirected
to /wp-login. Additionally, there is no way to send authentication requirements through Google's
servers, although I may be missing this piece completely. The only registration abilities I see
are to verify through Google that you are not a spammer and a white-list online service.

This is the url format I would use if I could send a request directly without authentication:
http://patchedupcreative.com/wp-admin/comment.php?c=34&action=approvecomment&_wpnonce=1245a280fa

Not being able to authenticate, the next best implementation is a 'two-click' solution. Using the 
url format above along with the schema for "ViewAction," a user will click the "Aprrove Comment"
button in the inbox which will open a new tab going directly to the apprval process. If the user
is logged in as an administrator (or other user capable of approving comments), the comment is 
immediately approved and the user is redirected to /wp-admin/editcomments.php with an approval
parameter. After closing the tab the user is returned to the inbox without having to open the body
of the email (only two clicks).

Unfortunately I have not been able to reproduce the needed 'nonce' after many attempts. Without it
I am not able to authenticate the step and the user is rejected with a WordPress "Are you sure you
want to do this?" error. I am creating a 'with-nonce' branch to continue experimenting with that
process, but as I don't believe I will be able to solve it before the deadline, I am leaving it as
an separate feature branch.

Without Google's ability to authenticate and my inability to reproduce the needed nonce, that leaves
the current working implementation as a 'three-click' solution. The schema for "ViewAction" is 
added with the url below:

http://patchedupcreative.com/wp-admin/comment.php?c=48&action=approve

The user clicks on the rendered "Approve Comment" button in the inbox which opens a new tab to the 
above url. The user clicks the "Approve Comment" button in the WordPress admin and they are 
redirected to the /wp-admin/editcomments.php page as before. Closing the tab (click three) brings them
back to their inbox.

I am not happy with this implementation and hope to find answers to get it down to the ideal one-click
solution. However, this implementation still works well as the email body is still never opened, and
there may be added functionality with being brought to the editcomments.php page, as each additional
comment can easily be approved from there. I know that was not the spirit of this assignment however.

== Installation ==

1. Upload `patched_up_email_formatter` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.3.3 =

* Sample email markup added along with some minor refactoring

= 0.3.2 =

* Fixes typos

= 0.3.1 =

* Significant refactoring and moves settings page to own class

= 0.3.0 =

* Adds "Test email" functionality with ajax call

= 0.2.1 =

* Adds instructions for registration
* Adds styling for settings page

= 0.2.0 =

* Adds blank settings page and settings link

= 0.1.1 =

* Changes email and from fields to use WordPress admin settings

= 0.1.0 =

* Lay down foundations including email content from WP base

== TODO ==

* Make 'amazing usability' on settings page
  * Test email functionality
    * Need to know if email actually successfully sent instead of just claiming it is
  * Need to have an 'activation' checkbox that only formats email if checked.
    Otherwise pluign should just be for test funcionality
* Multisite aware <- already is?
