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


= Current features =

* Admin Option Panel.
* Google Captcha
* Anti-spam Features
* Form Styling
* Form Export
* Form Edit Entries
* Hide Fields
* Fully responsive.
* Social icons
* Shortcode Attributes


[youtube https://www.youtube.com/watch?v=nOCU0rKOrfM]


If you have suggestions for a new add-on, feel free to email me at nfsarmento@gmail.com .
Or follow me on Twitter!
https://twitter.com/Nuno_M_Sarmento.

= Translation =
English

== installation ==
= How to use =
After installation add shortcode `[ns_contact_form]` on your page to display form or calling it on visual editor toolbar by clicking the form icon.

By default form submissions will be send to email from admin (set in Settings > General).

While adding the shortcode you can add several attributes to personalize your form.

While adding the widget you can add some additional information above your form.

= Shortcode attributes examples =
* Change email from admin: [ns_contact_form email_to="your-email-here"].
* Multiple email: [ns_contact_form email_to="first-email-here, second-email-here"].
* Change Name and Submit labels: [ns_contact_form label_name="Your Name" label_submit="Send"].
* Multiple email: [ns_contact_form email_to="first-email-here, second-email-here"].
* Hide subject field: [ns_contact_form hide_subject="true"].
* Hide phone field: [ns_contact_form hide_phone="true"].

= Labels and erros =
* Label attributes: label_name, label_email, label_subject, label_phone, label_message, label_submit.
* Label error attributes: error_name, error_email, error_subject, error_phone, error_message.
* Error and success message attributes: message_error, message_success.


== Frequently Asked Questions ==

= How do I add attributes? =
You can find more info about this at the installation section under shortcode attributes examples or on the plugin options section.

= How do I style my contact form? =
Go to form options and choose your style or if you want to get more advanced styling please instal "Nuno Sarmento CSS - JS".

= Can I hide Subject or Phone field? =
You can find more info about this at the installation section or on the plugin options section.

= Can user enter HTML in form? =
Yes, save HTML is allowed in message field.

= Can I use multiple shortcodes? =
Do not use multiple shortcodes on the same website, this might cause a conflict.

= Are form submissions listed in my plugin? =
Yes, they will be send to admin by mail and storage on plugin admin page -> Form Entries.

= Why am I not receiving form submissions? =
* Please look in your junk/spam folder.
* Please check the plugin option section and check the shortcode (attributes) for mistakes.
* Install another contact form plugin such as Contact Form 7 to determine if it is caused by this plugin or something else.
* Form submissions are send using the wp_mail function is a similar to php mail function, check with your hosting provider if this options is enabled on your server.

= Why does the google captcha not display properly? =
Check the captcha secret and private key on the plugin options page.

= Does this plugin has anti-spam features? =
Default WordPress sanitization and escaping functions are included.
It also contains 2 (invisible) honeypot fields (firstname and lastname).

= Other question or comment? =
Please open a topic in plugin forum.

== Changelog ==

= 1.0.0 =
Name standards.

== Screenshots ==
1. Nuno Sarmento Simple Contact Form - Options
2. Nuno Sarmento Simple Contact Form - Form Entries
3. Nuno Sarmento Simple Contact Form - Edit Entries
4. Nuno Sarmento Simple Contact Form - Export forms to CSV
5. Nuno Sarmento Simple Contact Form - Contact Form
