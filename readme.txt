=== Nuno Sarmento Simple Contact Form ===
Contributors: nunosarmento
Donate link: https://www.nuno-sarmento.com
Version: 1.0.0
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.7
Tested up to: 4.7.2
Stable tag: trunk
Tags: nuno, sarmento, contact, form, shortcode, captcha, email

Nuno Sarmento Simple Contact Form.

== Description ==

Nuno Sarmento is a simple contact form. Use the shortcode [ns_contact_form] to display form on page or post, the shortcode can be added manually or calling it on visual editor toolbar by clicking the form icon. All entries can be visualized on the form entries admin page plus all forms entries are editable and it can be exported to csv file

[youtube https://www.youtube.com/watch?v=nOCU0rKOrfM]

If you have suggestions for a new add-on, feel free to email me at nfsarmento@gmail.com .
Or follow me on Twitter!
https://twitter.com/Nuno_M_Sarmento.

= Question? =
Please take a look at the Installation and FAQ section.

= Translation =
English


== Installation ==
= How to use =
After installation add shortcode `[ns_contact_form]` on your page to display form or calling it on visual editor toolbar by clicking the form icon.

By default form submissions will be send to email from admin (set in Settings > General).

While adding the shortcode you can add several attributes to personalize your form.

While adding the widget you can add some additional information above your form.

= Shortcode attributes =
* Change email from admin: `[ns_contact_form email_to="your-email-here"]`
* Multiple email: `[ns_contact_form email_to="first-email-here, second-email-here"]`

You can also change message text or label text using an attribute.

* Label attributes: label_name, label_email, label_subject, label_captcha, label_message, label_submit
* Label error attributes: error_name, error_email, error_subject, error_captcha, error_message
* Error and success message attributes: message_error, message_success

= Examples =
* Change Name and Submit labels: `[ns_contact_form label_name="Your Name" label_submit="Send"]`
* Change captcha label: `[ns_contact_form label_captcha="Please enter %s"]`
* Change captcha label: `[ns_contact_form label_captcha="Please enter %s here"]`

= Subject field =
* Hide field: `[ns_contact_form hide_subject="true"]`


= Examples =
* Change email from admin: `email_to="your-email-here"`
* Multiple email: `email_to="first-email-here, second-email-here"`
* Change Name and Submit labels: `label_name="Your Name" label_submit="Send"`
* Change captcha label: `label_captcha="Please enter %s"`
* Change captcha label: `label_captcha="Please enter %s here"`
* Hide subject field: `hide_subject="true"`


== Frequently Asked Questions ==
= How do I set plugin language? =
Plugin uses the WP Dashboard language, set in Settings > General.

= How do I add attributes? =
You can find more info about this at the Installation section.

= How do I style my form? =
Go to form options and choose your style

= Can I hide Subject field? =
You can find more info about this at the Installation section.

= Can user enter HTML in form? =
Yes, save HTML is allowed in message field and widget info field.

= Can I use multiple shortcodes? =
Do not use multiple shortcodes on the same website. This might cause a conflict.

= Are form submissions listed in my plugin? =
Yes, they will be send to admin by mail and storage on plugin admin page -> Form Entries .

= Why am I not receiving form submissions? =
* Look also in your junk/spam folder.
* Check the Installation section and check shortcode (attributes) for mistakes.
* Install another contactform plugin (such as Contact Form 7) to determine if it's caused by my plugin or something else.
* Form submissions are send using the wp_mail function (similar to php mail function). Maybe your hosting provider disabled the php mail function, ask them to enable it.

= Why does the google captcha not display properly? =
Check the captcha scecret and private key on plugin options.

= Does this plugin has anti-spam features? =
Of course, the default WordPress sanitization and escaping functions are included.

It also contains 2 (invisible) honeypot fields (firstname and lastname) and a simple captcha sum.

= Other question or comment? =
Please open a topic in plugin forum.

== Changelog ==

= 1.0.0 =
Name standards.


== Screenshots ==
1. Very Simple Contact Form (Twenty Sixteen theme).
2. Very Simple Contact Form (Twenty Sixteen theme).
3. Very Simple Contact Form widget (Twenty Sixteen theme).
4. Very Simple Contact Form widget (dashboard).
